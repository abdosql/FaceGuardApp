<?php

namespace App\Services\settingsServices;


use App\Services\serviceInterfaces\SettingsServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class ScheduleSettingsService extends SettingsService
{
    protected function getSettingsFilePath(): string
    {
        return __DIR__.'/settings/scheduleSettings.json';
    }

    public function getSettings(string $key): string
    {
        return "";
    }

    public function setSetting(string $key, string $value): void
    {
        $this->settings[$key] = $value;
    }

    public function getAllSettings(): array
    {
        return $this->settings;
    }
}