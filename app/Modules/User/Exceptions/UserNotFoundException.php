<?php

namespace App\Modules\User\Exceptions;

use App\Exceptions\ModuleException;

class UserNotFoundException extends ModuleException
{
    public function __construct(int $id = null, ?string $email = null)
    {
        $message = 'User not found';
        if ($id) {
            $message = "User with ID {$id} not found";
        } elseif ($email) {
            $message = "User with email '{$email}' not found";
        }

        parent::__construct(
            message: $message,
            code: self::NOT_FOUND,
            model: 'User',
            context: ['id' => $id, 'email' => $email]
        );
    }
}
