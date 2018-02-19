<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;

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
        $coinTable = $schema->createTable('coin');
        $this->addIdToTable($coinTable);
        $coinTable->addColumn('name', 'string')->setNotnull(true);
        $coinTable->addColumn('slug', 'string')->setNotnull(true);
        $coinTable->addUniqueIndex(['name']);
        $coinTable->addUniqueIndex(['slug']);

        $exchangeTable = $schema->createTable('exchange');
        $this->addIdToTable($exchangeTable);
        $exchangeTable->addColumn('name', 'string')->setNotnull(true);
        $exchangeTable->addColumn('config', 'text')->setNotnull(true)->setComment('JSON Content');

        $pairTable = $schema->createTable('pair');
        $this->addIdToTable($pairTable);
        $pairTable->addColumn('coin_id_1', 'integer')->setNotnull(true)->setUnsigned(true);
        $pairTable->addColumn('coin_id_2', 'integer')->setNotnull(true)->setUnsigned(true);
        $pairTable->addColumn('decimal', 'integer')->setNotnull(true)->setUnsigned(true);
        $pairTable->addForeignKeyConstraint($coinTable, ['coin_id_1'], ['id']);
        $pairTable->addForeignKeyConstraint($coinTable, ['coin_id_2'], ['id']);

        $coinExchangeTable = $schema->createTable('coin_exchange');
        $this->addIdToTable($coinExchangeTable);
        $coinExchangeTable->addColumn('exchange_id', 'integer')->setNotnull(true)->setUnsigned(true);
        $coinExchangeTable->addColumn('pair_id', 'integer')->setNotnull(true)->setUnsigned(true);
        $coinExchangeTable->addColumn('exchange_symbol', 'string')->setNotnull(true);
        $coinExchangeTable->addForeignKeyConstraint($exchangeTable, ['exchange_id'], ['id']);
        $coinExchangeTable->addForeignKeyConstraint($pairTable, ['pair_id'], ['id']);
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
        $schema->dropTable('coin_exchange');
        $schema->dropTable('pair');
        $schema->dropTable('exchange');
        $schema->dropTable('coin');
    }
}
