<?php

namespace App\Model;

use Tightenco\Collect\Contracts\Support\Arrayable;

interface ModelInterface extends Arrayable
{
    public function toArray();
}
