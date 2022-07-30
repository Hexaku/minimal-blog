<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    //♫♪♬
    public const USERNAMES = [
        'Bach',
        'Beethoven',
        'Mozart',
        'Debussy',
        'Chopin',
        'Wagner',
        'Ravel',
        'Stravinsky',
        'Berlioz',
        'Chubert'
    ];

    public const ADMIN_EMAIL = 'admin@minimal.com';

    public function __construct(private UserPasswordHasherInterface $userPasswordHasherInterface)
    {}

    public function load(ObjectManager $manager): void
    {
        $admin = (new User())
            ->setUsername('Admin')
            ->setEmail(self::ADMIN_EMAIL)
            ->setRoles(['ROLE_ADMIN']);
        $adminPlainPassword = 'admin';
        $adminHashedPassword = $this->userPasswordHasherInterface->hashPassword($admin, $adminPlainPassword);
        $admin->setPassword($adminHashedPassword);

        $manager->persist($admin);

        foreach(self::USERNAMES as $userId => $username){
            $email = strtolower($username) . '@minimal.com';
            $user = (new User())
                ->setUsername($username)
                ->setEmail($email)
                ->setRoles(['ROLE_USER']);
            $userPlainPassword = 'user';
            $userHashedPassword = $this->userPasswordHasherInterface->hashPassword($user, $userPlainPassword);
            $user->setPassword($userHashedPassword);
            $this->addReference("user_$userId", $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
