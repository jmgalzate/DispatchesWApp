<?php 

namespace App\Service;

use App\Entity\Message\Payload;
use App\Entity\Message\Message;
use App\Service\LogService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;

class MessagesService
{
    private Message $message;
    
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly LogService $logger
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

        $this->message->setResponse(json_encode($responseData['Response']));

        $messageId = $this->saveMessage();

        return [
            'MessageId' => $messageId,
            'Status' => $responseData['Status'],
            'Response' => $responseData['Response']
        ];
    }

    private function sendMessage(string $endpoint, array $payload): array
    {
        try {
            $response = $this->client->request('POST', $endpoint, [
                'json' => ['_parameters' => $payload]
            ]);

            $this->message->setHttpStatus($response->getStatusCode());

            return [
                'Status' => 'Success',
                'Response' => $response->toArray()
            ];

        } catch (ServerException $e) {
            $this->message->setHttpStatus($e->getCode());
            $this->message->setResponse($e->getMessage());

            return [
                'Status' => 'Error',
                'Response' => $e->getMessage()
            ];

        } catch (ClientException $e) {
            $this->message->setHttpStatus($e->getCode());
            $this->message->setResponse($e->getMessage());

            return [
                'Status' => 'Error',
                'Response' => $e->getMessage()
            ];

        } catch (\Exception $e) {
            $this->message->setHttpStatus($e->getCode());
            $this->message->setResponse($e->getMessage());

            return [
                'Status' => 'Error',
                'Response' => $e->getMessage()
            ];
        }
        // $responseData = $response->toArray();
        // $header = $responseData["result"][0]["encabezado"];
        // $statusCode = $response->getStatusCode();
        // $body = $responseData["result"][0]["respuesta"]["datos"];
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