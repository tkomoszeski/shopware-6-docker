<?php declare(strict_types=1);

namespace TkBundleExample\Core\Content\Bundle\Aggregate\BundleTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class BundleTranslationCollection extends EntityCollection {

    protected function getExpectedClass(): string
    {
        //return entity
        return BundleTranslationEntity::class;
    }
}