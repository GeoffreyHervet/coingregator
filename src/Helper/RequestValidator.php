<?php

namespace App\Helper;

use App\Exception\ApiViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidator
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * RequestValidator constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function ensureValidRequest($request, $constraints = null): void
    {
        $errors = $this->validator->validate($request, $constraints);

        if ($errors->count()) {
            throw ApiViolationException::fromConstraintViolationList($errors);
        }
    }
}
