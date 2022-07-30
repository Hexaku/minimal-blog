<?php

namespace App\Tests\Repository;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostEntityTest extends KernelTestCase
{
    public function getPost(): Post
    {
        return (new Post())
            ->setTitle('Adventure is life.')
            ->setSynopsis('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam viverra consectetur gravida.')
            ->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam viverra consectetur gravida.');   
    }

    public function assertHasErrors(Post $post, int $errorsExpected = 0)
    {
        self::bootKernel();
        $errors = self::getContainer()->get('validator')->validate($post);
        $this->assertCount($errorsExpected, $errors);
    }

    public function testValidPost()
    {
        $post = $this->getPost();
        $this->assertHasErrors($post, 0);
    }

    public function testInvalidPostTitle()
    {
        // Title too short
        $post = $this->getPost()->setTitle('Me');
        $this->assertHasErrors($post, 1);

        // Title blank and too short
        $post = $this->getPost()->setTitle('');
        $this->assertHasErrors($post, 2);
    }

    public function testInvalidPostContent()
    {
        // Content too short
        $post = $this->getPost()->setContent('Me');
        $this->assertHasErrors($post, 1);

        // Content blank and too short
        $post = $this->getPost()->setContent('');
        $this->assertHasErrors($post, 2);
    }

    public function testInvalidPostSynopsis()
    {
        // Synopsis too short
        $post = $this->getPost()->setSynopsis('Me');
        $this->assertHasErrors($post, 1);

        // Synopsis blank and too short
        $post = $this->getPost()->setSynopsis('');
        $this->assertHasErrors($post, 2);
    }
}