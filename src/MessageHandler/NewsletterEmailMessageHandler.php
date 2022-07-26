<?php

namespace App\MessageHandler;

use App\Message\NewsletterEmailMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

final class NewsletterEmailMessageHandler implements MessageHandlerInterface
{
    public function __construct(private MailerInterface $mailer)
    {}

    public function __invoke(NewsletterEmailMessage $message)
    {
        $email = (new Email())
            ->to($message->getRecipient())
            ->subject($message->getTitle())
            ->text($message->getContent());

        // Delay to test async messages
        sleep(1);

        $this->mailer->send($email);
    }
}
