<?php

namespace App\Services\settingsServices;

use App\Services\serviceInterfaces\SettingsServiceInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

abstract class SettingsService implements SettingsServiceInterface
{
    public function __construct(private Filesystem $filesystem)
    {
    }

}