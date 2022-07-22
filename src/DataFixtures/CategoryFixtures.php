<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Service\Slugifier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_NAMES = ['Travel', 'Adventure', 'Exploration', 'Discovery', 'Mindset'];

    public function __construct(private Slugifier $slugifier)
    {}

    public function load(ObjectManager $manager): void
    {
        foreach(self::CATEGORY_NAMES as $categoryId => $categoryName){
            $category = (new Category())
                ->setName($categoryName)
                ->setSlug($this->slugifier->slugify($categoryName));
            $manager->persist($category);
            // References : category_0, category_1, category_2 ...
            $this->addReference("category_$categoryId", $category);
        }
        
        $manager->flush();
    }
}
