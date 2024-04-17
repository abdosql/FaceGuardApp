<?php

namespace App\Services\settingsServices;


use Symfony\Component\HttpFoundation\Request;

class ScheduleSettings extends SettingsService
{

    public function saveSetting(Request $request): void
    {
        dd($request->get("weekendDays"));
    }
}