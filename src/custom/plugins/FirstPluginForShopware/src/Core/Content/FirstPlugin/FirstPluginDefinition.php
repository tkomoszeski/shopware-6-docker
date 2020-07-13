<?php


namespace FirstPluginForShopware\Core\Content\FirstPlugin;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class FirstPluginDefinition extends EntityDefinition
{


    public function getEntityName(): string
    {
        //TODO
    }

    public function getCollectionClass(): string
    {
        //TODO
    }

    public function getEntityClass(): string
    {
        //TODO
    }

    protected function defineFields(): FieldCollection
    {
        /**
         * Documentation comment, not important for shopware,
         * but can be use to describe definition.
         * Type of Field     Name of field
         * IdField
         * FkField country_id
         * ManyToOneAssociation country to CountryDefinition
         */

        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new StringField('technical_name', 'technicalName'),
        ]);
    }
}