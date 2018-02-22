<?php

namespace App\Decorator;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class RequestDecorator extends Request
{
    /**
     * @var ParameterBag
     */
    public $payload;

    public function initialize(
        array $query = array(),
        array $request = array(),
        array $attributes = array(),
        array $cookies = array(),
        array $files = array(),
        array $server = array(),
        $content = null
    ) {
        parent::initialize($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->initPayload();
    }

    private function initPayload()
    {
        $payloadArray = $this->getContent();
        if (null !== $payloadArray) {
            $payloadArray = json_decode($payloadArray, true);
        }

        $this->payload = new ParameterBag($payloadArray ?: []);
    }

    public function get($key, $default = null)
    {
        if ($this !== $result = $this->payload->get($key, $this)) {
            return $result;
        }

        return parent::get($key, $default);
    }
}
