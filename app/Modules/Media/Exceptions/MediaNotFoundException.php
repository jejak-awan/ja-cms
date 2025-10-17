<?php

namespace App\Modules\Media\Exceptions;

use App\Exceptions\ModuleException;

class MediaNotFoundException extends ModuleException
{
    public function __construct(int $id = null, ?string $fileName = null)
    {
        $message = 'Media not found';
        if ($id) {
            $message = "Media with ID {$id} not found";
        } elseif ($fileName) {
            $message = "Media file '{$fileName}' not found";
        }

        parent::__construct(
            message: $message,
            code: self::NOT_FOUND,
            model: 'Media',
            context: ['id' => $id, 'file_name' => $fileName]
        );
    }
}
