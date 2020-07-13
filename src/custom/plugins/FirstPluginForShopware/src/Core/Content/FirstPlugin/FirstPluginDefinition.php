<?php


namespace FirstPluginForShopware\Core\Content\FirstPlugin;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Country\CountryDefinition;

class FirstPluginDefinition extends EntityDefinition
{


    public function getEntityName(): string
    {
        return "first_plugin";
    }

    public function getCollectionClass(): string
    {
        return FirstPluginCollection::class;
    }

    public function getEntityClass(): string
    {
        return FirstPluginEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        /**
         * Documentation comment, not important for shopware,
         * but can be use to describe definition.
         * Type of Field     Name of field
         * IdField      id
         * StringField technical_name
         * FkField country_id
         * ManyToOneAssociation country to CountryDefinition
         */
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new StringField('technical_name', 'technicalName'),
            new FkField('country_id','countryId', CountryDefinition::class,'id'),
            new ManyToOneAssociationField(
                'country',
                'country_id',
                CountryDefinition::class,
                'id',
                false
            )
        ]);
    }
}