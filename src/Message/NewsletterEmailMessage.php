<?php

namespace App\Message;

final class NewsletterEmailMessage
{
    public function __construct(private string $recipient, private string $title, private string $content)
    {}

    public function getRecipient(): string
    {
        return $this->recipient;
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
