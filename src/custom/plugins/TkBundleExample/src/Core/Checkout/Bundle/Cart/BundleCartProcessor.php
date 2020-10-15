<?php declare(strict_types=1);


namespace TkBundleExample\Core\Checkout\Bundle\Cart;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryInformation;
use Shopware\Core\Checkout\Cart\Delivery\Struct\DeliveryTime;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\LineItem\QuantityInformation;
use Shopware\Core\Checkout\Cart\Price\AbsolutePriceCalculator;
use Shopware\Core\Checkout\Cart\Price\PercentagePriceCalculator;
use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\AbsolutePriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\PercentagePriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\QuantityPriceDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use TkBundleExample\Core\Content\Bundle\BundleCollection;
use TkBundleExample\Core\Content\Bundle\BundleEntity;


class BundleCartProcessor implements CartProcessorInterface, CartDataCollectorInterface
{
    public const TYPE = 'tkbundle';
    public const DISCOUNT_TYPE = 'tkbundle-discount';
    public const DATA_KEY = 'tk_bundle-';
    public const DISCOUNT_TYPE_ABSOLUTE = 'absolute';
    public const DISCOUNT_TYPE_PERCENTAGE = 'percentage';

    /**
     * @var EntityRepositoryInterface
     */
    private $bundleRepository;

    /**
     * @var PercentagePriceCalculator
     */
    private $percentagePriceCalculator;

    /**
     * @var AbsolutePriceCalculator
     */
    private $absolutePriceCalculator;

    /**
     * @var QuantityPriceCalculator
     */
    private $quantityPriceCalculator;

    public function __construct(
        EntityRepositoryInterface $bundleRepository,
        PercentagePriceCalculator $percentagePriceCalculator,
        AbsolutePriceCalculator $absolutePriceCalculator,
        QuantityPriceCalculator $quantityPriceCalculator
    ) {
        $this->bundleRepository = $bundleRepository;
        $this->percentagePriceCalculator = $percentagePriceCalculator;
        $this->absolutePriceCalculator = $absolutePriceCalculator;
        $this->quantityPriceCalculator = $quantityPriceCalculator;
    }

    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavior): void
    {
        /**
         * TODO
         *  1. filter line items for type self::TYPE
         *  2. do an early return if no bundle items are found
         *  3.call fetch bundles
         *  4.fetch data to CartDataCollection (use self::DATA_KEY + bundle id using set method
         *  5. Iterate over all bundle line items and call the methods: enrichBundle , addMiss  and addDiscount
         */
        /** @var LineItemCollection $bundleLineItems */
        $bundleLineItems = $original->getLineItems()->filterType(self::TYPE); //ad.1

        // no bundles in cart? exit
        if (\count($bundleLineItems) === 0) {
            return;
        }

        // fetch missing bundle information from database
        $bundles = $this->fetchBundles($bundleLineItems, $data, $context);

        foreach ($bundles as $bundle) {
            // ensure all line items have a data entry
            $data->set(self::DATA_KEY . $bundle->getId(), $bundle);
        }

        foreach ($bundleLineItems as $bundleLineItem) {
            $bundle = $data->get(self::DATA_KEY . $bundleLineItem->getReferencedId());

            // enrich bundle information with quantity and delivery information
            $this->enrichBundle($bundleLineItem, $bundle);

            // add bundle products which are not already assigned
            $this->addMissingProducts($bundleLineItem, $bundle);

            // add bundle discount if not already assigned
            $this->addDiscount($bundleLineItem, $bundle, $context);
        }
    }

    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        // collect all bundle in cart
        /** @var LineItemCollection $bundleLineItems */
        $bundleLineItems = $original->getLineItems()
            ->filterType(self::TYPE);

        if (\count($bundleLineItems) === 0) {
            return;
        }

        foreach ($bundleLineItems as $bundleLineItem) {
            // first calculate all bundle product prices
            $this->calculateChildProductPrices($bundleLineItem, $context);

            // after the product prices calculated, we can calculate the discount
            $this->calculateDiscountPrice($bundleLineItem, $context);

            // at last we have to set the total price for the root line item (the bundle)
            $bundleLineItem->setPrice(
                $bundleLineItem->getChildren()->getPrices()->sum()
            );

            // afterwards we can move the bundle to the new cart
            $toCalculate->add($bundleLineItem);
        }
    }

    /**
     * Fetches all Bundles that are not already stored in data
     */
    private function fetchBundles(LineItemCollection $bundleLineItems, CartDataCollection $data, SalesChannelContext $context): EntityCollection
    {
        /**
         * TODO
         *  1. get bundle entity ids (every line item has a unique id/order number but the uuid of the bundle entity is saved under the referenceId property
         *  2. check if the bundle entity ids do already exist in the CartDataCollection
         *  3. create a Criteria with all ids that are not already existent in the data collection
         *  4. add products and delivery time association and get all entittes from the bundle repository
         */
        $bundleIds = $bundleLineItems->getReferenceIds(); //ad.1

        //ad 2 we don't want to refetch that from first bundle
        $filtered = [];
        foreach ($bundleIds as $bundleId) {
            // If data already contains the bundle we don't need to fetch it again
            if ($data->has(self::DATA_KEY . $bundleId)) {
                continue;
            }

            $filtered[] = $bundleId;
        }

        $criteria = new Criteria($filtered);
        $criteria->addAssociation('products');
        /** @var BundleCollection $bundles */
        $bundles = $this->bundleRepository->search($criteria, $context->getContext())->getEntities();

        return $bundles;
    }

    private function enrichBundle(LineItem $bundleLineItem, BundleEntity $bundle): void
    {
        //Line item is a class we can use of.
        //TODO set label bundle name if line item has no label
        if (!$bundleLineItem->getLabel()) {
            $bundleLineItem->setLabel($bundle->getTranslation('name'));
        }


        $bundleProducts = $bundle->getProducts();
        if ($bundleProducts === null) {
            throw new \RuntimeException(sprintf('Bundle "%s" has no products', $bundle->getTranslation('name')));
        }

        //TODO  ensure bundleLine item is removeable and set the delivery information , for the sake of simplicity  we will just use delivery information of the first product
        $firstBundleProduct = $bundleProducts->first();
        if ($firstBundleProduct === null) {
            throw new \RuntimeException(sprintf('Bundle "%s" has no products', $bundle->getTranslation('name')));
        }

        $firstBundleProductDeliveryTime = $firstBundleProduct->getDeliveryTime();
        if ($firstBundleProductDeliveryTime !== null) {
            $firstBundleProductDeliveryTime = DeliveryTime::createFromEntity($firstBundleProductDeliveryTime);
        }

        //TODO set quantity information = new QuanitityInformation()
        //by default any cart item is not removable
        $bundleLineItem->setRemovable(true)
            ->setStackable(true)
            ->setDeliveryInformation(
                new DeliveryInformation(
                    $firstBundleProduct->getStock(),
                    (float) $firstBundleProduct->getWeight(),
                    (bool) $firstBundleProduct->getShippingFree(),
                    $firstBundleProduct->getRestockTime(),
                    $firstBundleProductDeliveryTime
                )
            )
            ->setQuantityInformation(new QuantityInformation());
    }

    //adding the products assigned to the line item bundle, so we can display them bellow
    private function addMissingProducts(LineItem $bundleLineItem, BundleEntity $bundle): void
    {
        //get products
        $bundleProducts = $bundle->getProducts();
        if ($bundleProducts === null) {
            throw new \RuntimeException(sprintf('Bundle %s has no products', $bundle->getTranslation('name')));
        }

        //lets go with each one of them
        foreach ($bundleProducts->getIds() as $productId) {
            // if the bundleLineItem already contains the product we can skip it
            if ($bundleLineItem->getChildren()->has($productId)) {
                continue;
            }

            // the ProductCartProcessor will enrich the product further
            $productLineItem = new LineItem($productId, LineItem::PRODUCT_LINE_ITEM_TYPE, $productId);

            $bundleLineItem->addChild($productLineItem);
        }
    }

    private function addDiscount(LineItem $bundleLineItem, BundleEntity $bundle, SalesChannelContext $context): void
    {
        if ($this->getDiscount($bundleLineItem)) {
            return;
        }

        $discount = $this->createDiscount($bundle, $context);

        if ($discount) {
            $bundleLineItem->addChild($discount);
        }
    }

    private function getDiscount(LineItem $bundle): ?LineItem
    {
        return $bundle->getChildren()->get($bundle->getReferencedId() . '-discount');
    }

    private function createDiscount(BundleEntity $bundleData, SalesChannelContext $context): ?LineItem
    {
        if ($bundleData->getDiscount() === 0.0) {
            return null;
        }

        switch ($bundleData->getDiscountType()) {
            case self::DISCOUNT_TYPE_ABSOLUTE:
                $priceDefinition = new AbsolutePriceDefinition($bundleData->getDiscount() * -1, $context->getContext()->getCurrencyPrecision());
                $label = 'Absolute bundle voucher'; // can be translateable but we don't care
                break;

            case self::DISCOUNT_TYPE_PERCENTAGE:
                $priceDefinition = new PercentagePriceDefinition($bundleData->getDiscount() * -1, $context->getContext()->getCurrencyPrecision());
                $label = sprintf('Percental bundle voucher (%s%%)', $bundleData->getDiscount());
                break;

            default:
                throw new \RuntimeException('Invalid discount type.');
        }

        $discount = new LineItem(
            $bundleData->getId() . '-discount',
            self::DISCOUNT_TYPE,
            $bundleData->getId()
        );

        $discount->setPriceDefinition($priceDefinition)
            ->setLabel($label)
            ->setGood(false);

        return $discount;
    }

    private function calculateChildProductPrices(LineItem $bundleLineItem, SalesChannelContext $context): void
    {
        /** @var LineItemCollection $products */
        $products = $bundleLineItem->getChildren()->filterType(LineItem::PRODUCT_LINE_ITEM_TYPE);

        foreach ($products as $product) {
            $priceDefinition = $product->getPriceDefinition();
            if ($priceDefinition === null || !$priceDefinition instanceof QuantityPriceDefinition) {
                throw new \RuntimeException(sprintf('Product "%s" has invalid price definition', $product->getLabel()));
            }

            $product->setPrice(
                $this->quantityPriceCalculator->calculate($priceDefinition, $context)
            );
        }
    }

    private function calculateDiscountPrice(LineItem $bundleLineItem, SalesChannelContext $context): void
    {
        $discount = $this->getDiscount($bundleLineItem);

        if (!$discount) {
            return;
        }

        $childPrices = $bundleLineItem->getChildren()
            ->filterType(LineItem::PRODUCT_LINE_ITEM_TYPE)
            ->getPrices();

        $priceDefinition = $discount->getPriceDefinition();

        if (!$priceDefinition) {
            return;
        }

        switch (\get_class($priceDefinition)) {
            case AbsolutePriceDefinition::class:
                $price = $this->absolutePriceCalculator->calculate(
                    $priceDefinition->getPrice(),
                    $childPrices,
                    $context,
                    $bundleLineItem->getQuantity()
                );
                break;

            case PercentagePriceDefinition::class:
                $price = $this->percentagePriceCalculator->calculate(
                    $priceDefinition->getPercentage(),
                    $childPrices,
                    $context
                );
                break;

            default:
                throw new \RuntimeException('Invalid discount type.');
        }

        $discount->setPrice($price);
    }
}