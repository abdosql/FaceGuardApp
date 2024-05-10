<?php

namespace App\Controller;


use App\Services\llamaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LlamaController extends AbstractController
{
    public function __construct(private llamaService $llamaService)
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/llama', name: 'app_llama')]
    public function generateCompletion(): JsonResponse
    {
        // Example prompt, replace with dynamic data as necessary
        $userPrompt = "How can artificial intelligence benefit humanity?";

        $result = $this->llamaService->generateCompletion($userPrompt);
        return $this->json($result);
    }
}
