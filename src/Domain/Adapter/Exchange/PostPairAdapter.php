<?php

namespace App\Domain\Adapter\Exchange;

use App\Domain\Handler\Exchange\CreatePairHandler;
use App\Domain\Request\Exchange\CreatePairRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostPairAdapter
{
    /**
     * @var CreatePairHandler
     */
    private $handler;

    /**
     * PostPairAdapter constructor.
     *
     * @param CreatePairHandler $handler
     */
    public function __construct(CreatePairHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/exchanges/{exchange_id}/pair", requirements={"exchange_id": "^\d+$"}, methods="POST")
     */
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(
            $this->handler->handle(
                $this->createRequest($request)
            )
        );
    }

    private function createRequest(Request $request): CreatePairRequest
    {
        $createAssetRequest = new CreatePairRequest();
        $createAssetRequest->exchangeId = $request->get('exchange_id');
        $createAssetRequest->symbol = $request->get('symbol');

        return $createAssetRequest;
    }
}
