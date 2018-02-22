<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use App\Model\Asset;

class AssetRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * AssetRepository constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(Asset $asset): Asset
    {
        $this->connection->createQueryBuilder()
            ->insert('asset')
            ->values([
                'id' => ':id',
                'name' => ':name',
                'slug' => ':slug',
            ])
            ->setParameters($asset->toArray())
            ->execute();

        return $asset->setId((int)$this->connection->lastInsertId());
    }
}
