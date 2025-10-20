<?php

declare(strict_types=1);


namespace App\Validators;

use App\Contracts\ValidatorFactoryInterface;
use App\Contracts\ValidatorInterface;
use Psr\Container\ContainerInterface;

class ValidatorFactory implements ValidatorFactoryInterface
{
    public function __construct(private readonly ContainerInterface $container) {

    }

    public function make(string $class): ValidatorInterface
    {
        $validator = $this->container->get($class);

        if (!$validator instanceof ValidatorInterface) {
            throw new \RuntimeException('Failed to instantiate the validator class \'' . $class . '\'');
        }

        return $validator;
    }
}