<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryEntityTest extends KernelTestCase
{
    public function getCategory(): Category
    {
        $category = (new Category())
            ->setName('Habits');
        return $category;
    }

    public function assertHasErrors(Category $category, int $errorsExpected = 0)
    {
        self::bootKernel();
        $errors = self::getContainer()->get('validator')->validate($category);
        $this->assertCount($errorsExpected, $errors);
    }

    public function testValidCategory()
    {
        $category = $this->getCategory();
        $this->assertHasErrors($category, 0);
    }

    public function testInvalidCategoryName()
    {
        // Name too short
        $category = $this->getCategory()->setName('Me');
        $this->assertHasErrors($category, 1);

        // Name blank and too short
        $category = $this->getCategory()->setName('');
        $this->assertHasErrors($category, 2);
    }
}