<?php 

namespace App\Service;

use App\Entity\Contapyme\Payload;
use App\Entity\Message\Message;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;

class MessagesService
{
    private Message $message;
    
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function processRequest(int $messageType, int $orderNumber = null, string $endpoint, Payload $payload): array
    {
        $this->message = new Message();

        $this->message->setMessageType($messageType);
        $this->message->setOrderNumber($orderNumber);
        $this->message->setEndpoint($endpoint);
        $this->message->setPayload(json_encode([
            '_parameters' => [
                $payload->getParameters(),
                $payload->getAgent(),
                $payload->getIapp(),
                $payload->getRandom()
            ]
        ]));
        $this->message->setCreatedAt(new \DateTime());


        $responseData = $this->sendMessage(
                endpoint: $endpoint, 
                payload: [
                    $payload->getParameters(),
                    $payload->getAgent(),
                    $payload->getIapp(),
                    $payload->getRandom()
                ]
            );

        $this->message->setResponse(json_encode($responseData));

        $messageId = $this->saveMessage();

        return [
            'Status' => 'Success',
            'MessageId' => $messageId
        ];
    }

    private function sendMessage(string $endpoint, array $payload): array
    {
        $response = $this->client->request('POST', $endpoint, [
            'json' => ['_parameters' => $payload]
        ]);

        $this->message->setHttpStatus($response->getStatusCode());

        // $responseData = $response->toArray();
        // $header = $responseData["result"][0]["encabezado"];
        // $statusCode = $response->getStatusCode();
        // $body = $responseData["result"][0]["respuesta"]["datos"];

        return $response->toArray();
    }

    private function validateResponse(): void
    {
    }

    private function saveMessage(): int
    {
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