<?php

namespace App\Model;

class Exchange implements ModelInterface
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $config;

    public function __construct()
    {
        $this->config = [];
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'config' => $this->getConfig(),
        ];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return Exchange
     */
    public function setId(int $id): Exchange
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Exchange
     */
    public function setName(string $name): Exchange
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param string|array $config
     *
     * @return Exchange
     */
    public function setConfig($config = null): Exchange
    {
        if (!is_array($config)) {
            $config = json_decode($config, true);
        }

        $this->config = $config ?: [];

        return $this;
    }

}
