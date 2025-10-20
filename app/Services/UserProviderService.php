<?php

declare(strict_types=1);


namespace App\Services;

use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;
use App\DataObjects\RegisterData;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserProviderService implements UserProviderServiceInterface
{
    public function __construct(private readonly EntityManager $entityManager,
    ) {}

    public function getById(int $id): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function getByCredentials(array $credentials): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(
            ['email' => $credentials['email']],
        );
    }

    public function createUser(RegisterData $data): UserInterface
    {
        $user = new User();

        $user
            ->set_name($data->name)
            ->set_email($data->email)
            ->set_password(
                password_hash(
                    $data->password,
                    PASSWORD_BCRYPT,
                    ['cost' => 12],
                ),
            );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function existEmail($email): bool
    {
        $count = $this->entityManager->getRepository(User::class)->count(
            ['email' => $email],
        );

        return $count > 0;
    }
}