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
            $header = $responseData["result"][0]["encabezado"];
            $statusCode = $response->getStatusCode();
            $body = $responseData["result"][0]["respuesta"]["datos"];

            $this->logger->info('API request successful', [
                'endpoint' => $endpoint,
                'statusCode' => $statusCode,
                'params' => $params,
                'header' => $header
            ]);

            return new JsonResponse([
                'body' => $body
            ]);

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();

            $this->logger->error('API request failed', [
                'endpoint' => $endpoint,
                //'params' => $params,
                'error' => $errorMessage
            ]);

            return new JsonResponse([
                'error' => $errorMessage
            ]);
        }
    }
}