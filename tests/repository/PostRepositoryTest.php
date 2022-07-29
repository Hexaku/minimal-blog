<?php

namespace App\Tests\Repository;

use App\DataFixtures\PostFixtures;
use App\Entity\Category;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostRepositoryTest extends KernelTestCase
{
    // Check total count of posts
    public function testCount()
    {
        self::bootKernel();
        $postFixtures = self::getContainer()->get(PostFixtures::class);
        $totalPostsExpected = count($postFixtures::POST_TITLES);
        $totalPosts = self::getContainer()->get(PostRepository::class)->count([]);

        $this->assertEquals($totalPostsExpected, $totalPosts);
    }

    // Check that post should have a category
    public function testShouldHaveCategory()
    {
        self::bootKernel();
        $posts = self::getContainer()->get(PostRepository::class)->findAll();
        // Get latest post for test
        $post = $posts[0];

        $this->assertInstanceOf(Category::class, $post->getCategory());
    }
}