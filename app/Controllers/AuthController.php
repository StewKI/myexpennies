<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Valitron\Validator;

class AuthController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly EntityManager $entityManager,
    ) {}

    public function loginView(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'auth/login.twig');
    }

    public function registerView(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'auth/register.twig');
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // VALIDATION

        $v = new Validator($data);
        $v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
        $v->rule('email', 'email');
        $v->rule('equals', 'confirmPassword', 'password')->label("Confirm Password");
        $v->rule(function ($field, $value, $params, $fields) {
            $count = $this->entityManager->getRepository(User::class)->count(
                ['email' => $value],
            );

            return $count === 0;
        }, 'email')->message("User with given email already exists");

        if ($v->validate()) {
            echo "Successfully registered user";
        }
        else {
            throw new ValidationException($v->errors());
        }

        // CREATION

        $user = new User();

        $user
            ->set_name($data['name'])
            ->set_email($data['email'])
            ->set_password(
                password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12],
                ),
            );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $response;
    }
}