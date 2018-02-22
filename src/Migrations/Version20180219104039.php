<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use function fastcgi_finish_request;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180219104039 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $assetTable = $schema->createTable('asset');
        $this->addIdToTable($assetTable);
        $assetTable->addColumn('name', 'string')->setNotnull(true);
        $assetTable->addColumn('slug', 'string')->setNotnull(true);
        $assetTable->addUniqueIndex(['name']);
        $assetTable->addUniqueIndex(['slug']);

        $exchangeTable = $schema->createTable('exchange');
        $this->addIdToTable($exchangeTable);
        $exchangeTable->addColumn('name', 'string')->setNotnull(true);
        $exchangeTable->addColumn('config', 'text')->setNotnull(true)->setComment('JSON Content');


        $exchangePairTable = $schema->createTable('exchange_pair');
        $this->addIdToTable($exchangePairTable);
        $exchangePairTable->addColumn('exchange_id', 'integer')->setNotnull(true)->setUnsigned(true);
        $exchangePairTable->addColumn('pair_symbol', 'string')->setNotnull(true)->setUnsigned(true);
        $exchangePairTable->addColumn('asset_id_1', 'integer')->setNotnull(false)->setUnsigned(true);
        $exchangePairTable->addColumn('asset_id_2', 'integer')->setNotnull(false)->setUnsigned(true);
        $exchangePairTable->addColumn('watch', 'boolean')->setNotnull(true)->setDefault(false);

        $exchangePairTable->addForeignKeyConstraint($assetTable, ['asset_id_1'], ['id']);
        $exchangePairTable->addForeignKeyConstraint($assetTable, ['asset_id_2'], ['id']);
        $exchangePairTable->addForeignKeyConstraint($exchangeTable, ['exchange_id'], ['id']);

        $ohlcTable = $schema->createTable('ohlc');
        $this->addIdToTable($ohlcTable);
        $ohlcTable->addColumn('exchange_pair_id', 'integer')->setNotnull(true)->setUnsigned(true);
        $ohlcTable->addColumn('open', 'integer')->setNotnull(true)->setUnsigned(true);
        $ohlcTable->addColumn('high', 'integer')->setNotnull(true)->setUnsigned(true);
        $ohlcTable->addColumn('low', 'integer')->setNotnull(true)->setUnsigned(true);
        $ohlcTable->addColumn('close', 'integer')->setNotnull(true)->setUnsigned(true);
        $ohlcTable->addColumn('openedAt', 'datetime')->setNotnull(true)->setUnsigned(true)->setComment('UTC timezone');
        $ohlcTable->addColumn('closedAt', 'datetime')->setNotnull(true)->setUnsigned(true)->setComment('UTC timezone');

        $ohlcTable->addForeignKeyConstraint('exchange_pair', ['exchange_pair_id'], ['id']);
        $ohlcTable->addIndex(['exchange_pair_id']);
        $ohlcTable->addIndex(['exchange_pair_id', 'openedAt'], 'open_index');
        $ohlcTable->addIndex(['exchange_pair_id', 'closedAt'], 'close_index');
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
        $schema->dropTable('asset_exchange');
        $schema->dropTable('pair');
        $schema->dropTable('exchange');
        $schema->dropTable('asset');
    }
}
