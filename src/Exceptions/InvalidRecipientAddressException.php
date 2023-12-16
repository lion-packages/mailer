<?php

declare(strict_types=1);

namespace LionMailer\Exceptions;

use Exception;

class InvalidRecipientAddressException extends Exception
{
    public static function emptyRecipientsList(): self
    {
        return new self(
            'no recepients has been provided. Use `addAddress` method once or multiple times to specify as many recipients information as needed'
        );
    }
}
