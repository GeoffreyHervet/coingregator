<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use App\Model\Exchange;

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
        $this->connection->createQueryBuilder()
            ->insert('exchange')
            ->values([
                'id' => ':id',
                'name' => ':name',
                'config' => ':config',
            ])
            ->setParameters($exchange->toArray())
            ->execute();

        return $exchange->setId((int)$this->connection->lastInsertId());
    }
}
