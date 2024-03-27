<?php

namespace App\Services\UserServices;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

abstract class UserService implements UserServiceInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected SluggerInterface         $slugger,
        protected UserPasswordHasherInterface $passwordHasher
    ){}
    public function generateUniqueUsername(string $firstname, string $lastname): string
    {
        return $this->slugger->slug($firstname. " ". $lastname.rand(0, 10))->lower();
    }
    public function generateRandomPassword(User $user, int $length = 8): array
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = "";
        for ($i=0; $i < $length; $i++){
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        return [
            "password" => $password,
            "hashedPassword" => $this->passwordHasher->hashPassword($user, $password)
        ];
    }
}