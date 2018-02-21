<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use App\Model\Coin;

class CoinRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * CoinRepository constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(Coin $coin): Coin
    {
        $this->connection->createQueryBuilder()
            ->insert('coin')
            ->values([
                'id' => ':id',
                'name' => ':name',
                'slug' => ':slug',
            ])
            ->setParameters($coin->toArray())
            ->execute();

        return $coin->setId((int)$this->connection->lastInsertId());
    }
}
