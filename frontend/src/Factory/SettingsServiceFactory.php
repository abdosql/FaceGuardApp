<?php

namespace App\Factory;

use App\Services\serviceInterfaces\SettingsServiceInterface;
use App\Services\settingsServices\ScheduleSettingsService;
use Symfony\Component\Filesystem\Filesystem;

class SettingsServiceFactory
{
    private Filesystem $filesystem; // Property for the Filesystem dependency

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function createSettingsService(string $type): SettingsServiceInterface
    {
        return match ($type) {
            'schedule' => new ScheduleSettingsService($this->filesystem),
            default => throw new \InvalidArgumentException('Invalid settings service type.'),
        };
    }
}