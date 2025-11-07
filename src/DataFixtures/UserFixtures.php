<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $users = [
            ['email'=> 'alex@alexendretest.fr', 'password' => 'efrei_b3_in', 'roles' => ['ROLE_ADMIN']],
            ['email'=> 'bob@bobtest.fr', 'password' => 'efrei_b3_in', 'roles' => ['ROLE_USER']],
            ['email'=> 'charlie@charlietest.fr', 'password' => 'efrei_b3_in', 'roles' => ['ROLE_USER']],
            ['email'=> 'david@davidtest.fr', 'password' => 'efrei_b3_in', 'roles' => ['ROLE_USER']],
        ];


        foreach($users as $userArray){
            foreach($users as $userinfos) {
                $user = new User();
                $user->setEmail($userinfos['email']);

                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    $userinfos['password']
                );
                $user->setPassword($hashedPassword);
                $user->setRoles($userinfos['roles']);
                $manager->persist($user);
            }
            $manager->flush();

        }
    }
}