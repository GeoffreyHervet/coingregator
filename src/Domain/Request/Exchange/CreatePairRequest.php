<?php

namespace App\Domain\Request\Exchange;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePairRequest
{
    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     */
    public $exchangeId;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    public $symbol;
}
