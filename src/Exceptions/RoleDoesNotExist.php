<?php

declare(strict_types = 1);

namespace Voopoo\Casbin\Exceptions;

use InvalidArgumentException;

class RoleDoesNotExist extends InvalidArgumentException
{
    public static function named(string $roleName)
    {
        return new static("There is no role named `{$roleName}`.");
    }

    public static function withId(int $roleId)
    {
        return new static("There is no role with id `{$roleId}`.");
    }
}