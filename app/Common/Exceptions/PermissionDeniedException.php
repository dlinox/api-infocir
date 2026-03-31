<?php

namespace App\Common\Exceptions;

use Exception;

class PermissionDeniedException extends Exception
{
    protected string $permission;

    public function __construct(string $permission, ?string $message = null)
    {
        $this->permission = $permission;
        parent::__construct($message ?? "No autorizado");
    }

    public function getPermission(): string
    {
        return $this->permission;
    }
}
