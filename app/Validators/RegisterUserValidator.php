<?php

declare(strict_types=1);


namespace App\Validators;

use App\Contracts\UserProviderServiceInterface;
use App\Contracts\ValidatorInterface;
use App\DataObjects\RegisterData;
use App\Entity\User;
use App\Exception\ValidationException;
use Valitron\Validator;

class RegisterUserValidator implements ValidatorInterface
{
    public function __construct(
        private readonly UserProviderServiceInterface $userProvider,
    ) {}

    public function validate(array $data): array
    {
        $v = new Validator($data);
        $v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
        $v->rule('email', 'email');
        $v->rule('equals', 'confirmPassword', 'password')->label(
            "Confirm Password",
        );
        $v->rule(function ($field, $value, $params, $fields) {
            $exists = $this->userProvider->existEmail($value);

            return ! $exists;
        }, 'email')->message("User with given email already exists");

        if ( ! $v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}