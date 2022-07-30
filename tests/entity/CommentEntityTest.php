<?php

namespace App\Tests\Repository;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentEntityTest extends KernelTestCase
{
    public function getComment()
    {
        return (new Comment)
            ->setContent('Hello this is a comment');

    }

    public function assertHasErrors(Comment $comment, int $errorsExpected = 0)
    {
        self::bootKernel();
        $errors = self::getContainer()->get('validator')->validate($comment);
        $this->assertCount($errorsExpected, $errors);
    }

    public function testValidComment()
    {
        $comment = $this->getComment();
        $this->assertHasErrors($comment, 0);
    }

    public function testInvalidCommentContent()
    {
        // Content too short
        $comment = $this->getComment()->setContent('Me');
        $this->assertHasErrors($comment, 1);

        // Content blank and too short
        $comment = $this->getComment()->setContent('');
        $this->assertHasErrors($comment, 2);
    }
}