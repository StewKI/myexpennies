<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\AuthInterface;
use App\Contracts\ValidatorFactoryInterface;
use App\DataObjects\RegisterData;
use App\Exception\ValidationException;
use App\Validators\LoginUserValidator;
use App\Validators\RegisterUserValidator;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Valitron\Validator;

class AuthController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly AuthInterface $auth,
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
        $validator = $this->validatorFactory->make(RegisterUserValidator::class);

        $data = $validator->validate($request->getParsedBody());

        $this->auth->register(new RegisterData(
            $data['name'],
            $data['email'],
            $data['password'],
        ));


        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function login(Request $request, Response $response): Response
    {
        $validator = $this->validatorFactory->make(LoginUserValidator::class);

        $data = $validator->validate($request->getParsedBody());

        if (! $this->auth->attemptLogin($data)) {
            throw new ValidationException(['password' => ['Your email or password is incorrect']]);
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->auth->logout();

        return $response->withHeader('Location', '/')->withStatus(302);
    }
}