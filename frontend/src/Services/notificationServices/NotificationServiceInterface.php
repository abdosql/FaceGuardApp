<?php

namespace App\Services\notificationServices;

use Symfony\Component\Mailer\MailerInterface;

interface NotificationServiceInterface
{
    public function sendMessage(string $recipient, string $message, array $data): void;

}