<?php

declare(strict_types=1);

namespace Lion\Mailer\Exceptions;

use Exception;

/**
 * Exception for emails with empty destination email
 *
 * @package Lion\Mailer\Exceptions
 */
class InvalidFromAddressException extends Exception
{
    /**
     * Returns an object of type Exception with an error message
     *
     * @return self
     */
    public static function emptyFromAddress(): self
    {
        return new self(
            'No from address has been provided. Use the `from`  method to specify sender address information'
        );
    }
}
