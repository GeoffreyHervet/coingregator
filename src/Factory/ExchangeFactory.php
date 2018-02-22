<?php

namespace App\Factory;

use App\Model\Exchange;

class ExchangeFactory
{
    public static function create(string $name, array $config = []): Exchange
    {
        return (new Exchange())
            ->setName($name)
            ->setConfig($config);
    }

    public static function createWithId(int $id, string $name, array $config = []): Exchange
    {
        return static::create($name, $config)->setId($id);
    }
}
