<?php

namespace App\Tests\Repository;

use App\Entity\NewsletterSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsletterSubscriberEntityTest extends KernelTestCase
{
    public function getNewsletterSubscriber(): NewsletterSubscriber
    {
        return (new NewsletterSubscriber())
            ->setEmail('subscriber@minimal.com');
    }

    public function assertHasErrors(NewsletterSubscriber $newsletterSubscriber, int $errorsExpected = 0)
    {
        self::bootKernel();
        $errors = self::getContainer()->get('validator')->validate($newsletterSubscriber);
        $this->assertCount($errorsExpected, $errors);
    }

    public function testValidNewsletterSusbscriber()
    {
        $newsletterSubscriber = $this->getNewsletterSubscriber();
        $this->assertHasErrors($newsletterSubscriber, 0);
    }

    public function testInvalidNewsletterSusbscriberEmail()
    {
        // Not a valid email
        $newsletterSubscriber = $this->getNewsletterSubscriber()->setEmail('not good');
        $this->assertHasErrors($newsletterSubscriber, 1);

        // Email Blank
        $newsletterSubscriber = $this->getNewsletterSubscriber()->setEmail('');
        $this->assertHasErrors($newsletterSubscriber, 1);
    }
}