<?php

declare(strict_types=1);

namespace LionMailer\Exceptions;

class InvalidRecipientAddressException extends \Exception
{
    public static function emptyRecipientsList(): self
    {
        return new self("No recepients has been provided. Use `addAddress` method once or multiple times to specify as 
        many recipients information as needed.");
    }
}
