<?php declare(strict_types=1);

namespace TkBundleExample\Core\Content\Product;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use TkBundleExample\Core\Content\Bundle\Aggregate\BundleProduct\BundleProductDefinition;
use TkBundleExample\Core\Content\Bundle\BundleDefinition;

class ProductExtension extends EntityExtension {

    /**
     * @inheritDoc
     */
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add((
            new ManyToManyAssociationField(
                'bundles',
                BundleDefinition::class,
                BundleProductDefinition::class,
                'product_id',
                'bundle_id'
            ))->addFlags(new Inherited()));
    }

    /**
     * @inheritDoc
     */
    public function getDefinitionClass(): string
    {
        return ProductDefinition::class;
    }
}
