<?php

namespace App\Services;

use App\Entity\RFIDCard;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
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
    public function __construct(private HttpClientInterface $client, private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws RandomException
     */
    public function generateRandomRFID(): string
    {
        return (string)random_int(1000000000, 9999999999);
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

    public function saveStudentRfid(Student $student, string $rfidString):void
    {
        $rfid = new RFIDCard();
        $rfid->setRfidString($rfidString);
        $rfid->setStudent($student);
        $this->entityManager->persist($rfid);
        $this->entityManager->flush();
    }

    public function getRfidCard(string $rfidString): ?RFIDCard
    {
        return $this->entityManager->getRepository(RFIDCard::class)->findOneBy(['rfidString' => $rfidString]);
    }
}