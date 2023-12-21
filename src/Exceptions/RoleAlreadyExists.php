<?php

declare(strict_types = 1);

namespace Diandi\HyperfCasbin\Exceptions;

use InvalidArgumentException;

class RoleAlreadyExists extends InvalidArgumentException
{
    public static function create(string $roleName, string $guardName)
    {
        return new static("A role `{$roleName}` already exists for guard `{$guardName}`.");
    }
}
