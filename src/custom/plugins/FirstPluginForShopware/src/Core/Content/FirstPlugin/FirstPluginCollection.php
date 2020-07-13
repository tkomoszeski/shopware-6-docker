<?php


namespace FirstPluginForShopware\Core\Content\FirstPlugin;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(FirstPluginEntity $entity)
 * @method void              set(string $key, FirstPluginEntity $entity)
 * @method FirstPluginEntity[]    getIterator()
 * @method FirstPluginEntity[]    getElements()
 * @method FirstPluginEntity|null get(string $key)
 * @method FirstPluginEntity|null first()
 * @method FirstPluginEntity|null last()
 */
class FirstPluginCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return FirstPluginEntity::class;
    }
}