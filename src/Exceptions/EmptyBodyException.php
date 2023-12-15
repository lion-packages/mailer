<?php

declare(strict_types=1);

namespace LionMailer\Exceptions;

class EmptyBodyException extends \Exception
{
    public static function make(): self
    {
        return new self("Message body has not been specified. Use the `body` method to specify the message body.");
    }
}
