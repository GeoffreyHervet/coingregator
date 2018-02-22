<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use App\Model\Exchange;
use function json_encode;

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
}
