<?php declare(strict_types=1);

namespace TkBundleExample\Core\Content\Bundle;

use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Swag\BundleExample\Core\Content\Bundle\Aggregate\BundleTranslation\BundleTranslationCollection;

class BundleEntity extends Entity {

    use EntityIdTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var BundleTranslationCollection
     */
    protected $translations;

    /**
     * @var string
     */
    protected $discountType;

    /**
     * @var float
     */
    protected $discount;

    /**
     * @var ProductCollection|null
     */
    protected $products;

    /**
     * @return ProductCollection
     */
    public function getProducts(): ?ProductCollection
    {
        return $this->products;
    }

    /**
     * @param ProductCollection $products
     */
    public function setProducts(?ProductCollection $products): void
    {
        $this->products = $products;
    }




    public function getDiscountType(): string
    {
        return $this->discountType;
    }

    public function setDiscountType(string $discountType): void
    {
        $this->discountType = $discountType;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return BundleTranslationCollection
     */
    public function getTranslations(): BundleTranslationCollection
    {
        return $this->translations;
    }

    /**
     * @param BundleTranslationCollection $translations
     */
    public function setTranslations(BundleTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }




}