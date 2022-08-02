<?php

namespace App\Tests\Entity;

use App\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsletterEntityTest extends KernelTestCase
{
    public function getNewsletter(): Newsletter
    {
        return (new Newsletter())
            ->setTitle('This a newsletter')
            ->setContent('This a content');
    }

    public function assertHasErrors(Newsletter $newsletter, int $errorsExpected = 0)
    {
        self::bootKernel();
        $errors = self::getContainer()->get('validator')->validate($newsletter);
        $this->assertCount($errorsExpected, $errors);
    }

    public function testValidNewsletter()
    {
        $newsletter = $this->getNewsletter();
        $this->assertHasErrors($newsletter, 0);
    }

    public function testInvalidNewsletterTitle()
    {
        // Title too short
        $newsletter = $this->getNewsletter()->setTitle('Me');
        $this->assertHasErrors($newsletter, 1);

        // Title blank and too short
        $newsletter = $this->getNewsletter()->setTitle('');
        $this->assertHasErrors($newsletter, 2);
    }

    public function testInvalidNewsletterContent()
    {
        // Content blank
        $newsletter = $this->getNewsletter()->setContent('');
        $this->assertHasErrors($newsletter, 1);
    }
}