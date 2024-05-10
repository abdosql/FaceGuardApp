<?php

namespace App\Services;


use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class llamaService
{
    private string $apiKey;
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }


    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \Exception
     */
    public function generateCompletion(string $userPrompt): string
    {
        $exp = '
        {
   "dayName": {
      "session_Id": {
         "slot": "08:30 to 12:00",
         "group": "Group A",
         "branch": "Branch 1",
         "subject": "Mathematics",
         "teacher": "Mr. Smith"
      },
      "session_Id": {
         "slot": "14:00 to 16:00",
         "group": "Group B",
         "branch": "Branch 2",
         "subject": "Physics",
         "teacher": "Mrs. Johnson"
      }
   },
        ';
        $payload = [
            "model" => "meta-llama/Llama-3-8b-chat-hf", // Change this to your actual model
            "messages" => [
                [
                    "role" => "system",
                    "content" => "
                    You are an AI assistant tasked with generating a comprehensive time schedule for a school, The group should study just for half day (not all days morning, not all days evening)
                    Details:                    
                    Constraints:
                    Academic year: First year
                    Branch: Informatics
                    Group: Group A
                    Courses: ['PHP => teacher: Abdelaziz Saqqal', 'ASP => teacher: khiro', 'Spring => teacher: Haddouti', 'Economics => teacher: latifa', 'Business => teacher: mani', 'Big Data => teacher: Mohammed']
                    Session Duration: Each session lasts for 2 hours.
                    Pause Duration: Each break between sessions is 30 minutes.
                    Daily Time Slots:
                    Morning: 08:30 to 13:00.
                    Evening: 14:00 to 18:30.
                    Weekend Days to avoid: Saturday and Sunday.
                    Fall Semester: 10 Oct, 2023 to 01 Feb, 2024.
                    Spring Semester: 10 Feb, 2024 to 01 Jul, 2024.
                    Additional Constraints:
                    Ensure teachers do not have back-to-back classes without a break.
                    Task: Generate a detailed, conflict-free time schedule based on the provided entities and constraints. The schedule should optimize for the best use of resources while ensuring that all educational and administrative requirements are met. Include error handling for scenarios where constraints cannot be fully satisfied, suggesting alternative solutions.
                    
                    Output: Provide the generated schedules in a structured JSON format, like the following example:
                    .'$exp'.
                    Return the complete time schedules for each day of the week, branch, and group in this format.
        "
                ],
            ]
        ];

        try {
            $response = $this->client->request('POST', "https://api.together.xyz/v1/chat/completions", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $message = $response->toArray()["choices"][0]["message"]["content"];
            // Extract the JSON substring
            $startPos = strpos($message, '{');
            $endPos = strrpos($message, '}');
            dd(substr($message, $startPos, $endPos - $startPos + 1));
        } catch (HttpExceptionInterface $e) {
            $responseContent = $e->getResponse()->getContent(false);
            error_log('API Request failed: ' . $responseContent);
            throw new \Exception('API Request failed: ' . $responseContent, 0, $e);
        }
    }

}