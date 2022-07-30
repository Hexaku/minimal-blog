<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserEntityTest extends KernelTestCase
{
    public function getUser(): User
    {
        return (new User())
            ->setUsername('FlyingTuna')
            ->setEmail('flyingtuna@minimal.com');
    }

    public function assertHasErrors(User $user, int $errorsExpected = 0)
    {
        self::bootKernel();
        $errors = self::getContainer()->get('validator')->validate($user);
        $this->assertCount($errorsExpected, $errors);
    }

    public function testValidUser()
    {
        $user = $this->getUser();
        $this->assertHasErrors($user, 0);
    }

    public function testInvalidUserUsername()
    {
        // Username too short
        $user = $this->getUser()->setUsername('Me');
        $this->assertHasErrors($user, 1);

        // Username blank and too short
        $user = $this->getUser()->setUsername('');
        $this->assertHasErrors($user, 2);

        // Username not alphanumeric only
        $user = $this->getUser()->setUsername('U$ername!');
        $this->assertHasErrors($user, 1);
    }

    public function testInvalidUserEmail()
    {
        // Not a valid email
        $user = $this->getUser()->setEmail('not good');
        $this->assertHasErrors($user, 1);

        // Email blank
        $user = $this->getUser()->setEmail('');
        $this->assertHasErrors($user, 1);
    }
}