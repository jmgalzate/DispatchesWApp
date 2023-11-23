<?php

namespace App\Service;

use App\Entity\Message\Payload;
use App\Entity\Message\Message;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\Exception\TransportException;

class MessagesService
{
    private Message $message;

    public function __construct (
        private readonly HttpClientInterface    $httpClient,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function processRequest (int $messageType, int $orderNumber, string $endpoint, Payload $payload): array {
        $this->message = new Message();

        $this->message->setMessageType($messageType);
        $this->message->setOrderNumber($orderNumber);
        $this->message->setEndpoint($endpoint);
        $this->message->setCreatedAt(new \DateTime());

        $this->message->setPayload(json_encode([
            '_parameters' => [
                $payload->getParameters(),
                $payload->getAgent(),
                $payload->getIapp(),
                $payload->getRandom()
            ]
        ]));

        $responseData = $this->sendMessage(
            endpoint: $endpoint,
            payload: [
                $payload->getParameters(),
                $payload->getAgent(),
                $payload->getIapp(),
                $payload->getRandom()
            ]
        );

        $this->message->setResponse(json_encode($responseData['Response']));
        $messageId = $this->saveMessage();

        return [
            'MessageId' => $messageId,
            'Status' => $responseData['Status'],
            'Code' => $responseData['Code'],
            'Response' => $responseData['Response']
        ];
    }

    private function sendMessage (string $endpoint, array $payload): array {

        try {
            $response = $this->httpClient->request(
                method: 'POST',
                url: $endpoint,
                options: [
                    'json' => ['_parameters' => $payload],
                    'timeout' => 300, // Timeout set to 300 seconds
                ]
            );

            $responseCode = $response->getStatusCode();
            $responseBody = $response->toArray();
        } catch (TransportException|\Exception  $e) {
            $responseCode = $e->getCode();
            $responseBody = $e->getMessage();
        }

        $this->message->setHttpStatus($responseCode);

        return [
            'Status' => $responseCode === 200 ? 'Success' : 'Error',
            'Code' => $responseCode,
            'Response' => $responseBody
        ];

    }

    private function saveMessage (): int {
        $message = new Message();
        $message->setMessageType($this->message->getMessageType());
        $message->setOrderNumber($this->message->getOrderNumber());
        $message->setEndpoint($this->message->getEndpoint());
        $message->setHttpStatus($this->message->getHttpStatus());
        $message->setPayload($this->message->getPayload());
        $message->setResponse($this->message->getResponse());
        $message->setCreatedAt($this->message->getCreatedAt());

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message->getId();
    }

} 