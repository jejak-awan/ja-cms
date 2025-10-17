<?php

namespace App\Modules\Notification\Exceptions;

use App\Exceptions\ModuleException;

class NotificationNotFoundException extends ModuleException
{
    public function __construct(int $id = null)
    {
        $message = $id ? "Notification with ID {$id} not found" : 'Notification not found';

        parent::__construct(
            message: $message,
            code: self::NOT_FOUND,
            model: 'Notification',
            context: ['id' => $id]
        );
    }
}
