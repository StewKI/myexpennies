<?php

declare(strict_types=1);


namespace App;

use App\Contracts\AuthInterface;
use App\Contracts\SessionInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;
use App\DataObjects\RegisterData;
use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManager;

use function Symfony\Component\String\u;

class Auth implements AuthInterface
{
    private ?UserInterface $user = null;

    public function __construct(
        private readonly UserProviderServiceInterface $userProvider,
        private readonly SessionInterface $session,
    ) {}

    public function user(): ?UserInterface
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $userId = $this->session->get('user');

        if (! $userId) {
            return null;
        }

        $user = $this->userProvider->getById((int) $userId);

        if (! $user) {
            return null;
        }

        $this->user = $user;
        return $user;
    }

    public function attemptLogin(array $credentials): bool
    {
        $user = $this->userProvider->getByCredentials($credentials);

        if (!$user || ! $this->checkCredentials($user, $credentials)) {
            return false;
        }

        $this->login($user);

        return true;
    }

    public function checkCredentials(UserInterface $user, array $credentials): bool
    {
        return password_verify($credentials['password'], $user->get_password());
    }

    public function logout(): void
    {
        $this->session->regenerate();
        $this->session->forget('user');
        $this->user = null;
    }

    public function register(RegisterData $data): UserInterface
    {
        $user = $this->userProvider->createUser($data);

        $this->login($user);

        return $user;
    }

    public function login(UserInterface $user): void
    {
        $this->session->regenerate();
        $this->session->put('user', $user->get_id());

        $this->user = $user;
    }
}