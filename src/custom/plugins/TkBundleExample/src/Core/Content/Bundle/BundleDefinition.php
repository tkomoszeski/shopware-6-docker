<?php declare(strict_types=1);

namespace TkBundleExample\Core\Content\Bundle;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use TkBundleExample\Core\Content\Bundle\Aggregate\BundleProduct\BundleProductDefinition;
use TkBundleExample\Core\Content\Bundle\Aggregate\BundleTranslation\BundleTranslationDefinition;

class BundleDefinition extends EntityDefinition {

    public function getEntityName(): string
    {
        return 'tk_bundle';
    }

    public function getEntityClass(): string
    {
        return BundleEntity::class;
    }

    protected function defineFields(): \Shopware\Core\Framework\DataAbstractionLayer\FieldCollection
    {
        //(new StringField('name','name'))->addFlags(new Required()), //made this translatable
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            new TranslatedField('name'),
            (new StringField('discount_type', 'discountType'))->addFlags(new Required()),
            (new FloatField('discount', 'discount'))->addFlags(new Required()),
            new TranslationsAssociationField(BundleTranslationDefinition::class,'tk_bundle_id'),
            new ManyToManyAssociationField('products', ProductDefinition::class, BundleProductDefinition::class, 'bundle_id', 'product_id')
        ]);
    }
}