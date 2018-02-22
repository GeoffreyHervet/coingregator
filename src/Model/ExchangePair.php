<?php

namespace App\Model;

class ExchangePair implements ModelInterface
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var Exchange
     */
    private $exchange;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var Asset|null
     */
    private $firstAsset;

    /**
     * @var Asset|null
     */
    private $secondAsset;

    /**
     * @var bool
     */
    private $isWatching;

    public function __construct()
    {
        $this->isWatching = true;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'exchange' => $this->getExchange()->toArray(),
            'symbol' => $this->getSymbol(),
            'first_asset' => $this->getFirstAsset() ? $this->getFirstAsset()->toArray() : null,
            'second_asset' => $this->getSecondAsset() ? $this->getSecondAsset()->toArray() : null,
            'watching' => $this->isWatching(),
        ];
    }

    public function __toString(): string
    {
        return $this->getSymbol();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return ExchangePair
     */
    public function setId(int $id): ExchangePair
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Exchange
     */
    public function getExchange(): Exchange
    {
        return $this->exchange;
    }

    /**
     * @param Exchange $exchange
     *
     * @return ExchangePair
     */
    public function setExchange(Exchange $exchange): ExchangePair
    {
        $this->exchange = $exchange;

        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     *
     * @return ExchangePair
     */
    public function setSymbol(string $symbol): ExchangePair
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return Asset|null
     */
    public function getFirstAsset(): ?Asset
    {
        return $this->firstAsset;
    }

    /**
     * @param Asset|null $firstAsset
     *
     * @return ExchangePair
     */
    public function setFirstAsset(?Asset $firstAsset): ExchangePair
    {
        $this->firstAsset = $firstAsset;

        return $this;
    }

    /**
     * @return Asset|null
     */
    public function getSecondAsset(): ?Asset
    {
        return $this->secondAsset;
    }

    /**
     * @param Asset|null $secondAsset
     *
     * @return ExchangePair
     */
    public function setSecondAsset(?Asset $secondAsset): ExchangePair
    {
        $this->secondAsset = $secondAsset;

        return $this;
    }

    /**
     * @return bool
     */
    public function isWatching(): bool
    {
        return $this->isWatching;
    }

    /**
     * @param bool $isWatching
     *
     * @return ExchangePair
     */
    public function setIsWatching(bool $isWatching): ExchangePair
    {
        $this->isWatching = $isWatching;

        return $this;
    }


}
