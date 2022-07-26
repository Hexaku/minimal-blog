<?php

namespace App\DataFixtures;

use App\Entity\NewsletterSubscriber;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsletterSubscriberFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Creating 50 subscribers
        for($i=1; $i<=50; $i++){
            $newsletterSubscriber = (new NewsletterSubscriber())
                ->setEmail("subscriber$i@minimal.com");
            $manager->persist($newsletterSubscriber);
        }

        $manager->flush();
    }
}
