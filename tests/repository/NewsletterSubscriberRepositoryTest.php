<?php

namespace App\Tests\Repository;

use App\DataFixtures\NewsletterSubscriberFixtures;
use App\Repository\NewsletterSubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsletterSubscriberRepositoryTest extends KernelTestCase
{
    // Check total count of newsletter subscribers fixtures
    public function testCountFixtures()
    {
        self::bootKernel();
        $newsletterSubscriberFixtures = self::getContainer()->get(NewsletterSubscriberFixtures::class);
        $totalSubscribersExpected = $newsletterSubscriberFixtures::TOTAL_SUBSCRIBERS;
        $totalSubscribers = self::getContainer()->get(NewsletterSubscriberRepository::class)->count([]);
        
        $this->assertEquals($totalSubscribersExpected, $totalSubscribers);
    }
}