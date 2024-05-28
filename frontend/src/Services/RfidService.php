<?php

namespace App\Services;

use Random\RandomException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RfidService
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    /**
     * @throws RandomException
     */
    public function generateRandomRFID(): string
    {
        return bin2hex(random_bytes(10));
    }

    /**
     * @throws \Exception
     */
    public function writeRfid(string $rfid): array
    {
        try {
            $response = $this->client->request(
                'POST',
                'http://127.0.0.1:5000/write-rfid', // Flask API URL
                [
                    'json' => ['rfid' => $rfid],
                    'timeout' => 1200
                ]
            );

            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                return $response->toArray();
            } else {
                throw new \Exception("Failed to write RFID: Status code $statusCode");
            }
        } catch (TransportExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface | DecodingExceptionInterface | ClientExceptionInterface $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}