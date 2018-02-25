<?php

namespace App\Repository;

use App\Exception\NotFoundException;
use App\Factory\ExchangeFactory;
use App\Model\Exchange;
use Tightenco\Collect\Support\Collection;

class ExchangeRepository extends AbstractRepository
{
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
                ->select('*')
                ->from('exchange')
                ->execute()
                ->fetchAll()
        )->map(function(array $dbData): Exchange {
            return $this->dbRowToExchange($dbData);
        });
    }

    public function get(int $id): Exchange
    {
        $row = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('exchange')
            ->andWhere('id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetch() ?: [];

        if (empty(array_filter($row))) {
            throw new NotFoundException('exchange');
        }

        return $this->dbRowToExchange($row);
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
