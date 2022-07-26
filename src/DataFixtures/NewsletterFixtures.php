<?php

namespace App\DataFixtures;

use App\Entity\Newsletter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsletterFixtures extends Fixture
{
    public const NEWSLETTER_TITLES = [
        "This is my first newsletter !",
        "Why you should go on adventure too.",
        "How to really be free in 2022"
    ];
    
    public function load(ObjectManager $manager): void
    {
        foreach(self::NEWSLETTER_TITLES as $newsletterTitle){
            $newsletter = (new Newsletter())
                ->setTitle($newsletterTitle)
                ->setContent('In ornare ligula porta ex vehicula ultrices. Donec fermentum tellus quam, id lobortis dui ullamcorper sed. In tellus ex, condimentum ac mattis ac, fringilla non massa. Integer a sodales eros, in luctus enim. Fusce tortor elit, efficitur ut malesuada ac, euismod ut purus. Donec sed varius elit. Morbi sapien lorem, tincidunt et enim id, condimentum elementum leo. Quisque condimentum congue ex, a consectetur tortor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Etiam mollis nulla et lectus ultrices aliquet. Suspendisse potenti. Mauris et mattis elit, in aliquam nulla. Nam ex sapien, mattis a scelerisque eu, sollicitudin at mi. Proin tincidunt ornare enim, quis pretium odio suscipit nec. Donec hendrerit, mauris bibendum semper tempor, erat lorem lacinia tellus, a feugiat libero augue eu libero.')
                ->setIsSent(false);
            $manager->persist($newsletter);
        }

        $manager->flush();
    }
}
