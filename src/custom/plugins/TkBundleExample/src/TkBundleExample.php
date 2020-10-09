<?php declare(strict_types=1);

namespace TkBundleExample;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexerRegistry;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;


class TkBundleExample extends Plugin
{
    public function activate(ActivateContext $activateContext): void
    {
        $entityIndexerRegistry = $this->container->get(EntityIndexerRegistry::class);
        $entityIndexerRegistry->sendIndexingMessage(['product.indexer']);
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);
        $connection = $this->container->get(Connection::class);

        if($uninstallContext->keepUserData()) {
            return;
        }


        $this->dropTableIfExist("tk_bundle_product",$connection);
        $this->dropTableIfExist("tk_bundle_translation", $connection);
        $this->dropTableIfExist('tk_bundle',$connection);
        try{
            $connection->executeQuery('ALTER TABLE product DROP COLUMN bundles');
        }catch (\Exception $exception) {

        }



    }

    /**
     * @param string $tableName
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    private function dropTableIfExist(string $tableName, Connection $connection) {
        $connection->executeQuery('DROP TABLE IF EXISTS `'.$tableName."`");

    }
}