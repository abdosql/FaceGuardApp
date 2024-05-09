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
        return $this->settings[$key];
    }

    public function setSetting(string $key, string $value): void
    {
        $this->settings[$key] = $value;
    }

    public function getAllSettings(): array
    {
        return $this->settings;
    }

    public function getFallSemesterPeriod(): array
    {
        $semesterPeriod = explode(' to ', $this->getSettings('fallSemester'));
        $start = $semesterPeriod[0];
        $end = $semesterPeriod[1];
        return [
            "start" => \DateTime::createFromFormat('d M, Y', $start),
            "end" => \DateTime::createFromFormat('d M, Y', $end),
        ];
    }

    public function getSpringSemesterPeriod(): array
{
    $semesterPeriod = explode('to', $this->getSettings('springSemester'));
    return [
        "start" => \DateTime::createFromFormat('d:m:Y', $semesterPeriod[0]),
        "end" => \DateTime::createFromFormat('d:m:Y', $semesterPeriod[1]),
    ];
}
    public function getMorningSlot(): array
    {
        return [
            "start" => \DateTime::createFromFormat('H:i', $this->getSettings("morningStart")),
            "end" => \DateTime::createFromFormat('H:i', $this->getSettings("morningEnd")),
        ];
    }
    public function getEveningSlot(): array
    {
        return [
            "start" => \DateTime::createFromFormat('H:i', $this->getSettings("eveningStart")),
            "end" => \DateTime::createFromFormat('H:i', $this->getSettings("eveningEnd")),
        ];
    }
    public function getSessionDuration (): \DateTime|false
    {
        return \DateTime::createFromFormat('H:i', $this->getSettings("sessionDuration"));
    }
    public function pauseDuration(): \DateTime|false
    {
        return \DateTime::createFromFormat('H:i', $this->getSettings("pauseDuration"));
    }

    public function weekendDays(): string
    {
        return $this->getSettings("weekendDays");
    }

    public function getHolidays(): array
    {
        $holidays = explode(",", $this->getSettings("holidays"));
        $formattedHolidays = [];

        foreach ($holidays as $holiday) {
            $dateParts = explode(" ", $holiday);
            if (count($dateParts) === 2) {
                $date = \DateTime::createFromFormat('d M Y', $dateParts[0]);
                if ($date !== false) {
                    $formattedHolidays[] = $date;
                }
            }
        }

        return $formattedHolidays;
    }
}