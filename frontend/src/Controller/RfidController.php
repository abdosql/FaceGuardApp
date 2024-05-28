<?php

namespace App\Controller;

use App\Services\RfidService;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RfidController extends AbstractController
{
    public function __construct(private RfidService $rfidService)
    {
    }

    /**
     * @throws RandomException
     * @throws \Exception
     */
    #[Route('/generate-rfid', name: 'generate_rfid')]
    public function generateRfid(): JsonResponse
    {
        $randomRfid = $this->rfidService->generateRandomRfid();

        try {
            $result = $this->rfidService->writeRfid($randomRfid);

            if ($result['status'] === 'success') {
                return new JsonResponse($result, Response::HTTP_OK);
            } else {
                return new JsonResponse("Please check the connection with the Arduino", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
