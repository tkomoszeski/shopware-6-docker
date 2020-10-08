<?php declare(strict_types=1);

namespace TkBundleExample;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\Indexer\InheritanceIndexer;
use Shopware\Core\Framework\DataAbstractionLayer\Indexing\MessageQueue\IndexerMessageSender;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class TkBundleExample extends Plugin
{
    public function activate(ActivateContext $activateContext): void
    {
        $indexerMessageSender = $this->container->get(IndexerMessageSender::class);
        $indexerMessageSender->partial(new \DateTimeImmutable(),[InheritanceIndexer::getName()]);
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);
        $connection = $this->container->get(Connection::class);

        if($uninstallContext->keepUserData()) {
            $this->dropTableIfExist("tk_bundle_product",$connection);
            $this->dropTableIfExist("tk_bundle_translation", $connection);
        }


    }

    /**
     * @param string $tableName
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    private function dropTableIfExist(string $tableName, Connection $connection) {
        $connection->executeQuery('DTOP TABLE IF EXISTS '.$tableName);

    }
}