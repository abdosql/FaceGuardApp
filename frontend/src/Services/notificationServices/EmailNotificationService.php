<?php

namespace App\Services\notificationServices;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailNotificationService implements NotificationServiceInterface
{
    public function __construct(private MailerInterface $mailer,private Environment $twig)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendMessage(string $recipient, string $message, array $data): void
    {
        $template = $this->twig->render("email/index.html.twig",[
            "data" => $data,
            "message" => $message
        ]);
        $email = (new Email())
            ->from("abdelazizsaqqal@gmail.com")
            ->to($recipient)
            ->subject("Test Email")
            ->html($template);
        $this->mailer->send($email);
    }
}