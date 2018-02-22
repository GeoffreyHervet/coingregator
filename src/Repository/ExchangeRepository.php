<?php

namespace App\Repository;

use App\Factory\ExchangeFactory;
use Doctrine\DBAL\Connection;
use App\Model\Exchange;
use Tightenco\Collect\Support\Collection;

class ExchangeRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * ExchangeRepository constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(Exchange $exchange): Exchange
    {
        $exchangeParams = $exchange->toArray();
        $exchangeParams['config'] = json_encode($exchangeParams['config']);

        $this->connection->createQueryBuilder()
            ->insert('exchange')
            ->values([
                'id' => ':id',
                'name' => ':name',
                'config' => ':config',
            ])
            ->setParameters($exchangeParams)
            ->execute();

        return $exchange->setId((int)$this->connection->lastInsertId());
    }

    public function all(): Collection
    {
        return Collection::make(
            $this->connection->createQueryBuilder()
                ->select('id', 'name', 'config')
                ->from('exchange')
                ->execute()
                ->fetchAll()
        )->map(function(array $dbData): Exchange {
            return $this->dbRowToExchange($dbData);
        });
    }

    private function dbRowToExchange(array $dbRow): Exchange
    {
        return ExchangeFactory::createWithId(
            (int) $dbRow['id'],
            $dbRow['name'],
            json_decode($dbRow['config'], true) ?: []
        );
    }
}
