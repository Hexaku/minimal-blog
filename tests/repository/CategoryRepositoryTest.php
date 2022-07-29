<?php

namespace App\Tests\Repository;

use App\DataFixtures\CategoryFixtures;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryRepositoryTest extends KernelTestCase
{
    public function testCount()
    {
        self::bootKernel();
        $categoryFixtures = self::getContainer()->get(CategoryFixtures::class);
        $totalCategoriesExpected = count($categoryFixtures::CATEGORY_NAMES);
        $totalCategories = self::getContainer()->get(CategoryRepository::class)->count([]);
        
        $this->assertEquals($totalCategoriesExpected, $totalCategories);
    }
}
