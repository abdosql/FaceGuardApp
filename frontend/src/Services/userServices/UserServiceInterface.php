<?php

namespace App\Services\userServices;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

interface UserServiceInterface
{
    public function generateUniqueUsername(string $firstname, string $lastname): string;
    public function generateRandomPassword(User $user, int $length = 8): array;
}