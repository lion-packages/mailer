<?php

declare(strict_types=1);

namespace LionMailer\Exceptions;

use Exception;

class EmptyBodyException extends Exception
{
    public static function make(): self
    {
        return new self('message body has not been specified. Use the `body` method to specify the message body');
    }
}
