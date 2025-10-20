<?php

declare(strict_types=1);


namespace App\Contracts;

interface UserInterface
{
    public function get_id(): int;
    public function get_password(): string;
}