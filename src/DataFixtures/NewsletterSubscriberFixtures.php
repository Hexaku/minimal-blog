<?php

namespace App\DataFixtures;

use App\Entity\NewsletterSubscriber;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsletterSubscriberFixtures extends Fixture
{
    public const TOTAL_SUBSCRIBERS = 50;
    
    public function load(ObjectManager $manager): void
    {
        // Create 50 subscribers
        for($i=1; $i<=self::TOTAL_SUBSCRIBERS; $i++){
            $newsletterSubscriber = (new NewsletterSubscriber())
                ->setEmail("subscriber$i@minimal.com");
            $manager->persist($newsletterSubscriber);
        }

        $manager->flush();
    }
}
