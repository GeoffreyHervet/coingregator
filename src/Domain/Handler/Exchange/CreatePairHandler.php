<?php

namespace App\Domain\Handler\Exchange;

use App\Domain\Request\Exchange\CreatePairRequest;
use App\Factory\ExchangePairFactory;
use App\Helper\RequestValidator;
use App\Repository\ExchangePairRepository;
use App\Repository\ExchangeRepository;

class CreatePairHandler
{
    /**
     * @var RequestValidator
     */
    private $validator;

    /**
     * @var ExchangeRepository
     */
    private $exchangeRepository;

    /**
     * @var ExchangePairRepository
     */
    private $exchangePairRepository;

    /**
     * CreatePairHandler constructor.
     *
     * @param RequestValidator $validator
     * @param ExchangeRepository $exchange
     * @param ExchangePairRepository $exchangePairRepository
     */
    public function __construct(
        RequestValidator $validator,
        ExchangeRepository $exchange,
        ExchangePairRepository $exchangePairRepository
    ) {
        $this->validator = $validator;
        $this->exchangeRepository = $exchange;
        $this->exchangePairRepository = $exchangePairRepository;
    }

    public function handle(CreatePairRequest $request): array
    {
        $this->validator->ensureValidRequest($request);

        $exchange = $this->exchangeRepository->get((int)$request->exchangeId);
        $pair = ExchangePairFactory::create($exchange, $request->symbol, null, null);
        $this->exchangePairRepository->insert($pair);

        return $pair->toArray();
    }

}
