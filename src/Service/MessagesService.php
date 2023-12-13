<?php

namespace App\Service;

use App\Entity\Message\Payload;
use App\Entity\Message\Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MessagesService
{
    private Message $message;

    public function __construct (
        private readonly HttpClientInterface    $httpClient,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function processRequest (int $messageType, int $orderNumber, string $endpoint, Payload $payload): JsonResponse {

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

        $request = $this->sendMessage(
            endpoint: $endpoint,
            payload: [
                $payload->getParameters(),
                $payload->getAgent(),
                $payload->getIapp(),
                $payload->getRandom()
            ]
        );

        $this->message->setHttpStatus($request->getStatusCode());
        $this->message->setResponse($request->getContent());
        
        $messageId = $this->saveMessage();

        return new JsonResponse([
            "MessageId" => $messageId,
            "body" => $request->getContent()
        ], $request->getStatusCode());
    }
    
    private function sendMessage (string $endpoint, array $payload): JsonResponse {

        try{
            $request = $this->httpClient->request(
                method: 'POST',
                url: $endpoint,
                options: [
                    'json' => ['_parameters' => $payload]
                ]
            );
            
            $response = [
                "code" => $request->getStatusCode(),
                "body" => $request->getContent()
            ];
            
        } catch (TransportExceptionInterface $e) {
            $response = [
                "code" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "body" => $e->getMessage()
            ];
        }

        return new JsonResponse(["body" => $response['body']], $response['code']);
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