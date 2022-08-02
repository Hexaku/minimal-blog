<?php

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    // Check total count of users fixtures
    public function testCountFixtures()
    {
        self::bootKernel();
        $userFixtures = self::getContainer()->get(UserFixtures::class);
        $totalUsers = self::getContainer()->get(UserRepository::class)->count([]);
        // Users fixtures count + 1 admin
        $totalUsersExpected = count($userFixtures::USERNAMES) + 1;

        $this->assertEquals($totalUsersExpected, $totalUsers);
    }

    // Check every user should have role user
    public function testShouldHaveRoleUser()
    {
        self::bootKernel();
        $users = self::getContainer()->get(UserRepository::class)->findAll();
        foreach($users as $user){
            $this->assertContains('ROLE_USER', $user->getRoles());
        }
    }

    // Check admin user should have role admin
    public function testAdminShouldHaveRoleAdmin()
    {
        self::bootKernel();
        $userFixtures = self::getContainer()->get(UserFixtures::class);
        $adminArray = self::getContainer()->get(UserRepository::class)->findBy(['email' => $userFixtures::ADMIN_EMAIL], limit: 1);
        $admin = $adminArray[0];
        
        $this->assertContains('ROLE_ADMIN', $admin->getRoles());
    }
}