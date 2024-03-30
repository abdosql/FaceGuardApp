<?php

namespace App\Services\userServices;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminService extends UserService
{
    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($entityManager, $slugger, $passwordHasher);
    }

    public function getAllAdministrators(): array
    {
        return $this->entityManager->getRepository(Admin::class)->findAll();
    }

    public function getAdministratorById(int $id): ?Admin
    {
        return $this->entityManager->getRepository(Admin::class)->find($id);
    }

    public function saveAdministrator(Admin $admin): array
    {
        $password = $this->generateRandomPassword($admin);
        $admin->setUsername(
            $this->generateUniqueUsername(
                $admin->getFirstName(),
                $admin->getLastName())
        );
        $admin->setPassword($password["hashedPassword"]);
        $admin->setDtype("admin");
        $data = [
            "username" => $admin->getUsername(),
            "password" => $password["password"]
        ];
        $this->entityManager->persist($admin);
        $this->entityManager->flush();
        return $data;
    }

    public function deleteAdministrator(Admin $admin): void
    {
        $this->entityManager->remove($admin);
        $this->entityManager->flush();
    }

}