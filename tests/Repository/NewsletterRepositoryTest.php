<?php

namespace App\Tests\Repository;

use App\DataFixtures\NewsletterFixtures;
use App\Repository\NewsletterRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsletterRepositoryTest extends KernelTestCase
{
    // Check total count of newsletters fixtures
    public function testCountFixtures()
    {
        self::bootKernel();
        $newsletterFixtures = self::getContainer()->get(NewsletterFixtures::class);
        $totalNewslettersExpected = count($newsletterFixtures::NEWSLETTER_TITLES);
        $totalNewsletters = self::getContainer()->get(NewsletterRepository::class)->count([]);
        
        $this->assertEquals($totalNewslettersExpected, $totalNewsletters);
    }
}