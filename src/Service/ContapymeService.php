<?php

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ContapymeService
{
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger
    ) {}

    public function sendRequest(array $params, string $endpoint): JsonResponse
    {
        try {
            $response = $this->client->request('POST', $endpoint, [
                'json' => ['_parameters' => $params]
            ]);

            $responseData = $response->toArray();

            $this->logger->info('API request successful', [
                'endpoint' => $endpoint,
                'params' => $params,
                'responseData' => $responseData
            ]);

            return new JsonResponse([
                'path' => $endpoint,
                'parameters' => $params,
                'response' => $responseData
            ]);
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();

            $this->logger->error('API request failed', [
                'endpoint' => $endpoint,
                'params' => $params,
                'error' => $errorMessage
            ]);

            return new JsonResponse([
                'error' => $errorMessage
            ]);
        }
    }

    //TODO implement method for returning only the data from the response
}