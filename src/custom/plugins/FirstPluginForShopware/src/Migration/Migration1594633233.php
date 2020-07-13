<?php declare(strict_types=1);

namespace FirstPluginForShopware\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1594633233 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1594633233;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     */
    public function update(Connection $connection): void
    {
        // implement update
        $connection->exec("
            CREATE TABLE IF NOT EXISTS first_plugin 
                (
                    id                BINARY(16) NOT NULL,
                    technical_name    VARCHAR(255) NULL,
                    country_id        BINARY(16) NULL,
                    PRIMARY KEY (id),
                    CONSTRAINT country_id FOREIGN KEY (country_id)
                    REFERENCES country (id) ON DELETE RESTRICT ON UPDATE CASCADE 
                ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        );

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
