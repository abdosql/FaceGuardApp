<?php

namespace App\Services\serviceInterfaces;

use Symfony\Component\HttpFoundation\Request;

interface SettingsServiceInterface
{
    public function saveSettings(): void;
    public function getSettings(string $key): string;
    public function setSetting(string $key, string $value): void;
    public function getAllSettings(): array;
}