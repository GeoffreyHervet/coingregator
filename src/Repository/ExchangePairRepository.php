<?php

namespace App\Repository;

use App\Factory\AssetFactory;
use App\Factory\ExchangeFactory;
use App\Factory\ExchangePairFactory;
use App\Model\Asset;
use App\Model\Exchange;
use App\Model\ExchangePair;
use Doctrine\DBAL\Query\QueryBuilder;
use Tightenco\Collect\Support\Collection;

class ExchangePairRepository extends AbstractRepository
{
    public function byExchange(Exchange $exchange): Collection
    {
        return Collection::make($this->getQueryBuilder()
            ->andWhere('p.exchange_id = :exchange_id')
            ->setParameter('exchange_id', $exchange->getId())
            ->execute()
            ->fetchAll()
        )->map(function (array $dbRow) use ($exchange): ExchangePair {
            return $this->dbRowToObject($dbRow, $exchange);
        });
    }

    private function dbRowToObject(
        array $dbRow,
        Exchange $exchange = null,
        Asset $firstAsset = null,
        Asset $secondAsset = null
    ): ExchangePair {
        if ($exchange === null) {
            $exchange = ExchangeFactory::createWithId(
                (int) $dbRow['eid'],
                $dbRow['ename'],
                json_decode($dbRow['econfig'], true) ?: []
            );
        }

        if ($firstAsset === null) {
            $firstAsset = AssetFactory::createWithId(
                (int) $dbRow['a1id'],
                $dbRow['a1name'],
                $dbRow['a1slug']
            );
        }

        if ($secondAsset === null) {
            $secondAsset = AssetFactory::createWithId(
                (int) $dbRow['a2id'],
                $dbRow['a2name'],
                $dbRow['a2slug']
            );
        }

        return ExchangePairFactory::createWithId(
            (int) $dbRow['pid'],
            $exchange,
            $dbRow['psym'],
            $firstAsset,
            $secondAsset,
            (bool)$dbRow['pwatch']
        );
    }

    private function getQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(
                'p.id as pid', 'p.pair_symbol as psym', 'p.watch as pwatch',
                'e.id as eid', 'e.name as ename', 'e.config as econfig',
                'a1.id as a1id', 'a1.name as a1name', 'a1.slug as a1slug',
                'a2.id as a2id', 'a2.name as a2name', 'a2.slug as a2slug'
            )
            ->from('exchange_pair', 'p')
            ->innerJoin('p', 'exchange', 'e', 'e.id = p.exchange_id')
            ->leftJoin('p', 'asset', 'a1', 'a1.id = p.asset_id_1')
            ->leftJoin('p', 'asset', 'a2', 'a2.id = p.asset_id_2')
        ;
    }
}
