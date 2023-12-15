<?php

declare(strict_types=1);

namespace LionMailer\Exceptions;

class InvalidFromAddressException extends \Exception
{
    public static function emptyFromAddress(): self
    {
        return new self("No from address has been provided. Use the `from`  method to specify sender address
         information.");
    }
}
