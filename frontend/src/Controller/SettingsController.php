<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Factory\SettingsServiceFactory;
use App\Services\serviceInterfaces\SettingsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[AllowDynamicProperties] #[Route('/settings')]
class SettingsController extends AbstractController
{
    private SettingsServiceInterface $scheduleSettings;

    public function __construct(private readonly SettingsServiceFactory $settingsServiceFactory)
    {
        $this->scheduleSettings = $this->settingsServiceFactory->createSettingsService("schedule");
    }

    #[Route('/', name: 'app_settings_index')]
    public function index(): Response
    {
        $scheduleSettings = $this->scheduleSettings->getAllSettings();
        return $this->render('settings/index.html.twig', [
            'scheduleSettings' => $scheduleSettings,
        ]);
    }
    #[Route('/schedule', name: 'app_schedule_settings_index', methods: ["POST"])]

    public function saveScheduleSettings(Request $request): Response
    {
        foreach ($request->request->all() as $key => $value) {
            $this->scheduleSettings->setSetting($key, $value);
        }
        $this->scheduleSettings->saveSettings();
        return $this->redirectToRoute("app_settings_index");
    }
}
