<?php

namespace App\Domain\Request\Exchange;

use Symfony\Component\Validator\Constraints as Assert;

class GetExchangeRequest
{
    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     */
    public $id;
}
