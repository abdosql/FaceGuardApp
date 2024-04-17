<?php

namespace App\Services\serviceInterfaces;

use Symfony\Component\HttpFoundation\Request;

interface SettingsServiceInterface
{
    public function saveSetting(Request $request): void;
    public function getSettings();
}