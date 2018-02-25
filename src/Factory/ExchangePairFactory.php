<?php

namespace App\Factory;

use App\Model\Asset;
use App\Model\Exchange;
use App\Model\ExchangePair;

class ExchangePairFactory
{
    public static function createWithId(
        int $id,
        Exchange $exchange,
        string $symbol,
        ?Asset $firstAsset,
        ?Asset $secondAsset,
        bool $watching = false
    ): ExchangePair {
        return static::create($exchange, $symbol, $firstAsset, $secondAsset, $watching)->setId($id);
    }

    public static function create(
        Exchange $exchange,
        string $symbol,
        ?Asset $firstAsset,
        ?Asset $secondAsset,
        bool $watching = false
    ): ExchangePair {
        return (new ExchangePair())
            ->setExchange($exchange)
            ->setSymbol($symbol)
            ->setFirstAsset($firstAsset)
            ->setSecondAsset($secondAsset)
            ->setIsWatching($watching);
    }
}
