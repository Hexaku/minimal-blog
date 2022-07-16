<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasherInterface)
    {}

    public function load(ObjectManager $manager): void
    {
        $admin = (new User())
            ->setUsername('admin')
            ->setEmail('admin@minimal.com')
            ->setRoles(['ROLE_ADMIN']);
        $adminPlainPassword = 'admin';
        $adminHashedPassword = $this->userPasswordHasherInterface->hashPassword($admin, $adminPlainPassword);
        $admin->setPassword($adminHashedPassword);

        $manager->persist($admin);

        $user = (new User())
            ->setUsername('user')
            ->setEmail('user@minimal.com')
            ->setRoles(['ROLE_USER']);
        $userPlainPassword = 'user';
        $userHashedPassword = $this->userPasswordHasherInterface->hashPassword($user, $userPlainPassword);
        $user->setPassword($userHashedPassword);

        $manager->persist($user);

        $manager->flush();
    }
}
