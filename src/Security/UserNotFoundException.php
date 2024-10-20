<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserNotFoundException extends AuthenticationException
{
    public function __construct(string $message = "User not found.")
    {
        parent::__construct($message);
    }
}