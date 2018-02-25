<?php

namespace App\Factory;

use App\Model\Asset;

class AssetFactory
{
    public static function create(string $name, string $slug): Asset
    {
        return (new Asset())
            ->setName($name)
            ->setSlug($slug);
    }

    public static function createWithId(int $id, string $name, string $slug): Asset
    {
        return static::create($name, $slug)->setId($id);
    }
}
