<?php

namespace App\Message;

final class NewsletterEmailMessage
{
    public function __construct( private string $sender, private string $receiver, private string $title, private string $content)
    {}

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getReceiver(): string
    {
        return $this->receiver;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
