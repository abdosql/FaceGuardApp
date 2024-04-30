<?php

namespace App\Services\settingsServices;

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
    public function getMorningSlot(): array
    {
        return [
            "start" => $this->getSettings("morningStart"),
            "end" => $this->getSettings("morningEnd"),
        ];
    }
    public function getEveningSlot(): array
    {
        return [
            "start" => $this->getSettings("eveningStart"),
            "end" => $this->getSettings("eveningEnd"),
        ];
    }
    public function getSessionDuration (): string
    {
        return $this->getSettings("sessionDuration");
    }
    public function pauseDuration(): string
    {
        return $this->getSettings("pauseDuration");
    }

    public function weekendDays(): string
    {
        return $this->getSettings("weekendDays");
    }

    public function getHolidays(): array
    {
        $holidays = explode(",", $this->getSettings("holidays"));
    }
}