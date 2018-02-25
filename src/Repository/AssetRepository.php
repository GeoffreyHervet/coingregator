<?php

namespace App\Repository;

use App\Factory\AssetFactory;
use App\Model\Asset;
use Tightenco\Collect\Support\Collection;

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

    public function all(): Collection
    {
        return Collection::make(
                $this->connection->createQueryBuilder()
                    ->select('*')
                    ->from('asset')
                    ->execute()
                    ->fetchAll()
            )
            ->map(function (array $rowDb): Asset {
                return $this->dbRowToAsset($rowDb);
            });
    }

    private function dbRowToAsset(array $rowDb): Asset
    {
        return AssetFactory::createWithId(
            (int)$rowDb['id'],
            $rowDb['name'],
            $rowDb['slug']
        );
    }
}
