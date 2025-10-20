<?php

declare(strict_types=1);


namespace App\Contracts;

use App\Auth;
use App\DataObjects\RegisterData;

interface AuthInterface
{
    public function user(): ?UserInterface;

    public function attemptLogin(array $credentials): bool;

    public function checkCredentials(
        UserInterface $user,
        array $credentials
    ): bool;

    public function logout(): void;

    public function register(RegisterData $data): UserInterface;

    public function login(UserInterface $user): void;
}