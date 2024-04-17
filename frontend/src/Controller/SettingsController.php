<?php

namespace App\Controller;

use App\Services\settingsServices\ScheduleSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/settings')]
class SettingsController extends AbstractController
{
    public function __construct(private ScheduleSettings $scheduleSettings)
    {
    }

    #[Route('/', name: 'app_settings_index')]
    public function index(): Response
    {
        return $this->render('settings/index.html.twig', [
            'controller_name' => 'SettingsController',
        ]);
    }
    #[Route('/schedule', name: 'app_schedule_settings_index', methods: ["POST"])]

    public function saveScheduleSettings(Request $request)
    {
        //
    }
}
