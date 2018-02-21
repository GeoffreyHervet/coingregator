<?php

namespace App\Factory;

use App\Model\Coin;

class CoinFactory
{
    public static function create(string $name, string $slug): Coin
    {
        return (new Coin())
            ->setName($name)
            ->setSlug($slug);
    }

    public static function createWithId(int $id, string $name, string $slug): Coin
    {
        return static::create($name, $slug)->setId($id);
    }
}
