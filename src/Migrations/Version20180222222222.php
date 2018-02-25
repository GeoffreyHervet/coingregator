<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180222222222 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $exchangeTable = $schema->getTable('exchange');
        $exchangeTable->addUniqueIndex(['name'], 'uniqname');

        $exchangePairTable = $schema->getTable('exchange_pair');
        $exchangePairTable->addUniqueIndex(['exchange_id', 'pair_symbol'], 'uniqexchangesymbol');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $exchangeTable = $schema->getTable('exchange');
        $exchangeTable->dropIndex('uniqname');

        $exchangePairTable = $schema->getTable('exchange_pair');
        $exchangePairTable->dropIndex('uniqexchangesymbol');
    }
}
