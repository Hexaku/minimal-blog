<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = ['Travel', 'Adventure', 'Exploration', 'Discovery', 'Mindset'];

    public function load(ObjectManager $manager): void
    {
        foreach(self::CATEGORIES as $key => $categoryName){
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            // References : category_0, category_1, category_2 ...
            $this->addReference("category_$key", $category);
        }
        
        $manager->flush();
    }
}
