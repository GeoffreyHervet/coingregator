<?php

namespace App\Repository;

use App\Model\Asset;

class AssetRepository extends AbstractRepository
{
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
