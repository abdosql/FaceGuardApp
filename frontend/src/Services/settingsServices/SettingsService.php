<?php

namespace App\Services\settingsServices;

use App\Services\serviceInterfaces\SettingsServiceInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class SettingsService implements SettingsServiceInterface
{
    protected array $settings = [];
    protected Filesystem $filesystem; // Property for the Filesystem dependency

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->loadSettings();
    }

    protected function loadSettings(): void
    {
        $filePath = $this->getSettingsFilePath();
        if (file_exists($filePath)) {
            $this->settings = json_decode(file_get_contents($filePath), true);
        } else {
            throw new \RuntimeException('Settings file not found.');
        }
    }
    public function saveSettings(): void
    {
        $updatedSettings = json_encode($this->settings, JSON_PRETTY_PRINT);
        $this->filesystem->dumpFile($this->getSettingsFilePath(), $updatedSettings);
    }

    abstract protected function getSettingsFilePath(): string;

}