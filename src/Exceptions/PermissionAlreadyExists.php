<?php

declare(strict_types = 1);

namespace Voopoo\Casbin\Exceptions;

use InvalidArgumentException;

class PermissionAlreadyExists extends InvalidArgumentException
{
    public static function create(string $permissionName, string $guardName)
    {
        return new static("A `{$permissionName}` permission already exists for guard `{$guardName}`.");
    }
}
