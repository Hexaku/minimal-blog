<?php

namespace App\Tests\Repository;

use App\DataFixtures\CommentFixtures;
use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentRepositoryTest extends KernelTestCase
{
    // Check that we have 3 comments fixtures per post
    public function testCountFixturesPerPost()
    {
        self::bootKernel();
        $posts = self::getContainer()->get(PostRepository::class)->findAll();
        foreach($posts as $post){
            $comments = $post->getComments();
            $totalCommentsPerPost = count($comments);
            $this->assertEquals(3, $totalCommentsPerPost);
        }
    }

    // Check that every comment should have a post
    public function testShouldHavePost()
    {
        self::bootKernel();
        $comments = self::getContainer()->get(CommentRepository::class)->findAll();
        foreach($comments as $comment){
            $post = $comment->getPost();
            $this->assertInstanceOf(Post::class, $post);
        }
    }
}