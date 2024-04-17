<?php

namespace App\Services\serviceInterfaces;

use App\Entity\User;

interface UserServiceInterface
{
    public function generateUniqueUsername(string $firstname, string $lastname): string;
    public function generateRandomPassword(User $user, int $length = 8): array;
}