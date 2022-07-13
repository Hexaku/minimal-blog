<?php

namespace App\DataFixtures;

use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for($i=0; $i<10; $i++){
            $post = (new Post())
                ->setTitle('Lorem ipsum dolor sit amet, consectetur adipiscing elit.')
                ->setSynopsis('Aenean pellentesque molestie interdum. Proin in tincidunt dolor, in elementum dui.')
                ->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pellentesque molestie interdum. Proin in tincidunt dolor, in elementum dui. Vestibulum luctus sapien at aliquet vestibulum. Ut a arcu fermentum, pulvinar leo in, tempor neque. Quisque id sapien sodales, finibus eros vitae, euismod urna. Pellentesque volutpat porttitor justo, in ultrices tortor ullamcorper id. Vivamus sed ipsum ac elit luctus tincidunt vitae a nisi. In nulla nibh, mattis vel orci a, tempus placerat diam.
            Vestibulum et turpis commodo, ultrices massa vel, rutrum tortor. Nulla et est eu tellus auctor suscipit. Fusce sed elit et elit sodales ultricies a sed sapien. Interdum et malesuada fames ac ante ipsum primis in faucibus. In id nibh vel nibh tempor hendrerit. Mauris quis odio vitae elit placerat varius at a lacus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Fusce in euismod orci. Nunc molestie, orci in bibendum efficitur, sapien nisi efficitur turpis, a blandit mauris elit ac nisl. Quisque dui mi, dapibus ut tincidunt vel, ullamcorper eget ex. Curabitur molestie velit eget finibus scelerisque. Nullam faucibus euismod tortor, vitae laoreet mauris aliquet ac.
            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin pulvinar purus eu lorem consectetur, facilisis aliquet eros consectetur. Mauris sollicitudin massa ac erat faucibus, ac sodales ligula mattis. Sed a bibendum sapien. Sed sed tortor nunc. Nulla consectetur eleifend enim vel imperdiet. Ut vel ornare ex. Proin ultricies turpis id lectus lobortis, at molestie mauris lacinia. Mauris ac sapien quis libero luctus auctor. Mauris lobortis pellentesque nisi et fringilla. Suspendisse potenti. In commodo sapien libero, a efficitur ligula scelerisque vitae. Fusce ac eleifend sapien. Pellentesque aliquam finibus blandit.'
            );
            // Random timestamp between 01/01/2020 and 01/07/2022
            $randTimestamp = mt_rand(1577833200, 1656626400);
            $post->setCreatedAt((new DateTime())->setTimestamp($randTimestamp));
            // Random category
            $randCategoryId = rand(0,4);
            $post->setCategory($this->getReference("category_$randCategoryId"));

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }
}
