<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScheduleApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getSchedule(array $data): array
    {
        $response = $this->client->request(
            'POST',
            'http://127.0.0.1:5000/schedule', // Replace with your actual API URL
            [
                'json' => $data,
                'timeout' => 600
            ]
        );

        $statusCode = $response->getStatusCode();
        // Check if the request was successful
        if ($statusCode === 200) {
            return $response->toArray();
        } else {
            // Handle errors or unexpected status codes
            throw new \Exception("Failed to fetch schedule: Status code $statusCode");
        }
    }
}