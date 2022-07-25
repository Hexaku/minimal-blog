<?php

namespace App\DataFixtures;

use App\Entity\Newsletter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsletterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=0; $i<10; $i++){
            $newsletter = (new Newsletter())
                ->setTitle('Vestibulum cursus rhoncus interdum.')
                ->setContent('In ornare ligula porta ex vehicula ultrices. Donec fermentum tellus quam, id lobortis dui ullamcorper sed. In tellus ex, condimentum ac mattis ac, fringilla non massa. Integer a sodales eros, in luctus enim. Fusce tortor elit, efficitur ut malesuada ac, euismod ut purus. Donec sed varius elit.')
                ->setIsSent(false);
            $manager->persist($newsletter);
        }

        $manager->flush();
    }
}
