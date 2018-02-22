<?php

namespace App\Domain\Handler\Exchange;

use App\Domain\Request\Exchange\GetExchangeRequest;
use App\Helper\RequestValidator;
use App\Model\Exchange;
use App\Repository\ExchangePairRepository;
use App\Repository\ExchangeRepository;
use Tightenco\Collect\Support\Collection;

class GetExchangeHandler
{
    /**
     * @var ExchangeRepository
     */
    private $exchangeRepository;

    /**
     * @var ExchangePairRepository
     */
    private $exchangePairRepository;

    /**
     * @var RequestValidator
     */
    private $validator;

    /**
     * GetExchangeHandler constructor.
     *
     * @param ExchangeRepository $exchangeRepository
     * @param ExchangePairRepository $exchangePairRepository
     * @param RequestValidator $validator
     */
    public function __construct(
        ExchangeRepository $exchangeRepository,
        ExchangePairRepository $exchangePairRepository,
        RequestValidator $validator
    ) {
        $this->exchangeRepository = $exchangeRepository;
        $this->exchangePairRepository = $exchangePairRepository;
        $this->validator = $validator;
    }

    public function handle(GetExchangeRequest $request): array
    {
        $this->validator->ensureValidRequest($request);

        $exchange = $this->exchangeRepository->get((int) $request->id);

        return [
            'exchange' => $exchange->toArray(),
            'pairs' => $this->getPairs($exchange),
        ];
    }

    private function getPairs(Exchange $exchange): array
    {
        return $this->exchangePairRepository->byExchange($exchange)->toArray();
    }
}
