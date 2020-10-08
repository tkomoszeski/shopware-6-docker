<?php declare(strict_types=1);

namespace TkBundleExample\Core\Content\Bundle\Aggregate\BundleTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use TkBundleExample\Core\Content\Bundle\BundleDefinition;

class BundleTranslationDefinition extends EntityTranslationDefinition {

    public function getEntityName(): string
    {
        return "tk_bundle_translation";
    }

    public function getCollectionClass(): string
    {
        return BundleTranslationDefinition::class;
    }

    public function getEntityClass(): string
    {
        return BundleTranslationEntity::class;
    }

    public function getParentDefinitionClass(): string
    {
        return BundleDefinition::class;
    }


    protected function defineFields(): FieldCollection
    {
       return new FieldCollection([(new StringField('name','name'))->addFlags(new Required())]);
    }
}