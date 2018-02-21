<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180221115727 extends AbstractMigration
{    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $ohlcTable = $schema->createTable('ohlc');
        $this->addIdToTable($ohlcTable);
        $ohlcTable->addColumn('coin_exchange_id', 'integer')->setNotnull(true)->setUnsigned(true);
        $ohlcTable->addColumn('open', 'integer')->setNotnull(true)->setUnsigned(true);
        $ohlcTable->addColumn('high', 'integer')->setNotnull(true)->setUnsigned(true);
        $ohlcTable->addColumn('low', 'integer')->setNotnull(true)->setUnsigned(true);
        $ohlcTable->addColumn('close', 'integer')->setNotnull(true)->setUnsigned(true);
        $ohlcTable->addColumn('openedAt', 'datetime')->setNotnull(true)->setUnsigned(true)->setComment('UTC timezone');
        $ohlcTable->addColumn('closedAt', 'datetime')->setNotnull(true)->setUnsigned(true)->setComment('UTC timezone');

        $ohlcTable->addForeignKeyConstraint('coin_exchange', ['coin_exchange_id'], ['id']);

        $ohlcTable->addIndex(['coin_exchange_id']);
        $ohlcTable->addIndex(['coin_exchange_id', 'openedAt'], 'open_index');
        $ohlcTable->addIndex(['coin_exchange_id', 'closedAt'], 'close_index');
    }

    private function addIdToTable(Table $table)
    {
        $table
            ->addColumn('id', 'integer')
            ->setAutoincrement(true)
            ->setUnsigned(true)
            ->setNotnull(true);
        $table->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('ohlc');
    }
}
