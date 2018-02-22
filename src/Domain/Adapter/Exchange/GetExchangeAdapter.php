<?php

namespace App\Domain\Adapter\Exchange;

use App\Domain\Handler\Exchange\GetExchangeHandler;
use App\Domain\Request\Exchange\GetExchangeRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class GetExchangeAdapter
{
    /**
     * @var GetExchangeHandler
     */
    private $handler;

    /**
     * GetExchangeAdapter constructor.
     *
     * @param GetExchangeHandler $handler
     */
    public function __construct(GetExchangeHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/exchanges/{exchange_id}", requirements={"exchange_id": "^\d+$"}, methods="GET")
     */
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(
            $this->handler->handle(
                $this->createRequest($request)
            )
        );
    }


    private function createRequest(Request $request): GetExchangeRequest
    {
        $exchangeRequest = new GetExchangeRequest();
        $exchangeRequest->id = $request->get('id');

        return $exchangeRequest;
    }
}
