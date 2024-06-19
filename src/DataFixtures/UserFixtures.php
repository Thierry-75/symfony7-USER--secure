<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('thierrydothee@protonmail.com')
              ->setRoles(['ROLE_USER,ROLE_ADMIN'])
              ->setVerified(true)
              ->setCreatedAt(new \DateTimeImmutable())
              ->setPlainPassword('password');
       
              $users [] = $admin;
        $manager->persist($admin);
        for($i = 0; $i < 4; $i++) 
        {
            $user = new User();
            $user->setEmail($this->faker->email())
                 ->setRoles(['ROLE_USER'])
                 ->setVerified(true)
                 ->setCreatedAt(new \DateTimeImmutable())
                 ->setPlainPassword('password');
                 $users [] = $user;
                 $manager->persist($user);
        }
      
        $manager->flush();
    }
}
