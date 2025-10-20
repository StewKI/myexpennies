<?php

declare(strict_types=1);


namespace App\Contracts;

use App\DataObjects\RegisterData;

interface UserProviderServiceInterface
{
    public function getById(int $id): ?UserInterface;

    public function getByCredentials(array $credentials): ?UserInterface;

    public function createUser(RegisterData $data): UserInterface;

    public function existEmail($email) : bool;
}