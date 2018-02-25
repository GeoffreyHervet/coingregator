<?php

namespace App\Exception;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Doctrine\Common\Inflector\Inflector;

class ApiViolationException extends \DomainException
{
    /**
     * @var ArrayCollection
     */
    private $errors;

    public function __construct(ArrayCollection $errors, int $code = Response::HTTP_BAD_REQUEST)
    {
        $this->errors = $errors;
        $this->setCode($code);
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public static function fromInvalidJson(): self
    {
        $errors = new ArrayCollection();
        $errors->set('payload', new ArrayCollection());
        $errors->get('payload')->add('invalid_json');

        return new self($errors);
    }

    public static function singleFieldViolation(string $field, string $error): self
    {
        $errors = new ArrayCollection();
        $errors->set($field, new ArrayCollection());
        $errors->get($field)->add($error);

        return new self($errors);
    }

    public static function fromConstraintViolationList(ConstraintViolationListInterface $constraintViolationList): self
    {
        $errors = new ArrayCollection();
        $code = Response::HTTP_BAD_REQUEST;

        /** @var ConstraintViolation $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $errorPath = Inflector::tableize($constraintViolation->getPropertyPath());
            $errorValue = $constraintViolation->getMessage();

            if (!$errors->containsKey($errorPath)) {
                $errors->set($errorPath, new ArrayCollection());
            }

            if (is_numeric($constraintViolation->getCode())) {
                $code = (int) $constraintViolation->getCode();
            }

            $errors->get($errorPath)->add($errorValue);
        }

        return new self($errors, $code);
    }

    public function toArray(): array
    {
        return $this->errors
            ->map(function (ArrayCollection $collection) {
                return $collection->getValues();
            })
            ->toArray();
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
