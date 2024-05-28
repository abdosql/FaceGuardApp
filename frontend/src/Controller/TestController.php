<?php

namespace App\Controller;

use App\Services\AutoGeneratorsServices\TimeScheduleGenerator;
use App\Services\ScheduleApiService;
use App\Services\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TestController extends AbstractController
{
    public function __construct(private TimeScheduleGenerator $scheduleService,private ScheduleApiService $scheduleApiService)
    {
    }

    /**
     * @throws \Exception
     */
    #[Route('/testst', name: 'app_test')]
    public function index(): Response
    {
        $schedule = [
            "status" => "success",
            "data" => [
                "First Year" => [
                    "Génie Industriel" => [
                        "A" => [
                            [
                                [null, null, null, ["Physique Générale", "Concepcion Carter", 6]]
                            ],
                            [
                                [["Dessin Industriel", "Heath Miller", 24], ["Mathématiques Appliquées", "Chauncey Stanton", 8], null, ["Chimie Industrielle", "Crawford Leannon", 2]]
                            ],
                            [
                                [["Chimie Industrielle", "Crawford Leannon", 10], null, null, ["Gestion de la Production", "Maximo Weber", 5]]
                            ],
                            [
                                [null, null, ["Anglais Technique", "Kiara Bogan", 3], ["Contrôle de Qualité", "Alexandria Huels", 28]]
                            ],
                            [
                                [null, null, ["Physique Générale", "Concepcion Carter", 20], null]
                            ],
                            [
                                [null, null, null, ["Contrôle de Qualité", "Alexandria Huels", 25]]
                            ]
                        ]
                    ],
                    "Génie Informatique" => [
                        "A" => [
                            [
                                [["Anglais", "Walker Jacobson", 25], null, null, null]
                            ],
                            [
                                [null, ["Génie Logiciel", "Darlene Schumm", 20], ["Algorithmique", "Leo Powlowski", 12], null]
                            ],
                            [
                                [null, null, null, null]
                            ],
                            [
                                [null, ["Mathématiques", "Monty Predovic", 16], null, null]
                            ],
                            [
                                [["Algorithmique", "Leo Powlowski", 5], null, ["Physique", "Josefina Auer", 23], null]
                            ],
                            [
                                [null, ["Systèmes d'exploitation", "Alana Schmidt", 24], ["Programmation", "Jana Beer", 0], null]
                            ]
                        ]
                    ]
                ],
                "Second Year" => [
                    "Génie Industriel" => [
                        "A" => [
                            [
                                [["Contrôle de Qualité", "Alexandria Huels", 15], null, ["Contrôle de Qualité", "Alexandria Huels", 25], ["Gestion de la Production", "Maximo Weber", 2]]
                            ],
                            [
                                [null, null, ["Mathématiques Appliquées", "Chauncey Stanton", 21], ["Physique Générale", "Concepcion Carter", 22]]
                            ],
                            [
                                [["Dessin Industriel", "Heath Miller", 5], null, ["Chimie Industrielle", "Crawford Leannon", 19], null]
                            ],
                            [
                                [null, ["Physique Générale", "Concepcion Carter", 10], null, ["Chimie Industrielle", "Crawford Leannon", 16]]
                            ],
                            [
                                [null, null, ["Anglais Technique", "Kiara Bogan", 8], null]
                            ],
                            [
                                [null, null, null, null]
                            ]
                        ]
                    ],
                    "Génie Informatique" => [
                        "A" => [
                            [
                                [null, null, null, null]
                            ],
                            [
                                [null, ["Algorithmique", "Leo Powlowski", 10], null, null]
                            ],
                            [
                                [null, ["Algorithmique", "Leo Powlowski", 8], null, null]
                            ],
                            [
                                [["Mathématiques", "Monty Predovic", 7], null, null, null]
                            ],
                            [
                                [["Génie Logiciel", "Darlene Schumm", 25], null, ["Anglais", "Walker Jacobson", 27], ["Systèmes d'exploitation", "Alana Schmidt", 0]]
                            ],
                            [
                                [null, ["Programmation", "Jana Beer", 3], ["Physique", "Josefina Auer", 13], null]
                            ]
                        ]
                    ],
                    "Génie Mécaniques" => [
                        "A" => [
                            [
                                [null, ["Thermodynamique", "Nicola Heaney", 22], null, ["Lean Manufacturing", "Hubert O'Reilly", 27]]
                            ],
                            [
                                [["Mécanique des Fluides", "Baby Treutel", 16], null, ["Lean Manufacturing", "Hubert O'Reilly", 27], null]
                            ],
                            [
                                [null, ["Gestion de la Maintenance", "Cortney Funk", 5], null, null]
                            ],
                            [
                                [null, null, ["Ingénierie des Systèmes", "Gretchen Shanahan", 18], null]
                            ],
                            [
                                [null, null, null, ["Mécanique des Fluides", "Baby Treutel", 23]]
                            ],
                            [
                                [["Résistance des Matériaux", "Courtney Aufderhar", 10], null, null, ["Matériaux de Construction", "Yadira Altenwerth", 12]]
                            ]
                        ]
                    ]
                ],
                "Third Year" => [
                    "Génie Industriel" => [
                        "A" => [
                            [
                                [null, null, null, null]
                            ],
                            [
                                [["Chimie Industrielle", "Crawford Leannon", 20], null, null, null]
                            ],
                            [
                                [null, ["Mathématiques Appliquées", "Chauncey Stanton", 14], ["Physique Générale", "Concepcion Carter", 13], null]
                            ],
                            [
                                [["Chimie Industrielle", "Crawford Leannon", 15], ["Contrôle de Qualité", "Alexandria Huels", 20], ["Contrôle de Qualité", "Alexandria Huels", 2], ["Gestion de la Production", "Maximo Weber", 17]]
                            ],
                            [
                                [null, null, null, ["Anglais Technique", "Kiara Bogan", 14]]
                            ],
                            [
                                [["Physique Générale", "Concepcion Carter", 7], ["Dessin Industriel", "Heath Miller", 21], null, null]
                            ]
                        ]
                    ],
                    "Génie Informatique" => [
                        "A" => [
                            [
                                [["Algorithmique", "Leo Powlowski", 19], null, null, null]
                            ],
                            [
                                [null, null, null, null]
                            ],
                            [
                                [null, ["Physique", "Josefina Auer", 12], null, ["Systèmes d'exploitation", "Alana Schmidt", 21]]
                            ],
                            [
                                [null, null, ["Algorithmique", "Leo Powlowski", 20], ["Mathématiques", "Monty Predovic", 9]]
                            ],
                            [
                                [null, null, ["Programmation", "Jana Beer", 2], null]
                            ],
                            [
                                [["Anglais", "Walker Jacobson", 9], null, ["Génie Logiciel", "Darlene Schumm", 1], null]
                            ]
                        ]
                    ],
                    "Génie Mécaniques" => [
                        "A" => [
                            [
                                [null, ["Ingénierie des Systèmes", "Gretchen Shanahan", 17], null, null]
                            ],
                            [
                                [null, ["Mécanique des Fluides", "Baby Treutel", 17], null, null]
                            ],
                            [
                                [["Mécanique des Fluides", "Baby Treutel", 22], null, null, ["Lean Manufacturing", "Hubert O'Reilly", 20]]
                            ],
                            [
                                [["Matériaux de Construction", "Yadira Altenwerth", 18], null, ["Gestion de la Maintenance", "Cortney Funk", 0], ["Résistance des Matériaux", "Courtney Aufderhar", 10]]
                            ],
                            [
                                [null, null, null, ["Lean Manufacturing", "Hubert O'Reilly", 21]]
                            ],
                            [
                                [null, ["Thermodynamique", "Nicola Heaney", 1], null, null]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'timetable' => $schedule
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \Exception
     */
    #[Route('/genTest', name: 'apptest')]

    public function generate(): Response
    {
        set_time_limit(1200);
        // Call the generateSchedules method from the service
        $schedules = $this->scheduleService->generateSchedules();
        $file = fopen('schedule.txt', 'w');
        if (empty($schedules)) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Could not generate schedule, check your data or API.'
            ], 500);
        }
        $this->scheduleService->saveSchedules($schedules, 1);
        // Return the schedule data in a JSON response
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'timetable' => $schedules
        ]);
    }
}
