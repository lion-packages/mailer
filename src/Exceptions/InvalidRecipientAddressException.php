<?php

declare(strict_types=1);

namespace Lion\Mailer\Exceptions;

use Exception;

/**
 * Exception for emails with empty destination email
 *
 * @package Lion\Mailer\Exceptions
 */
class InvalidRecipientAddressException extends Exception
{
    /**
     * Returns an object of type Exception with an error message
     *
     * @return self
     */
    public static function emptyRecipientsList(): self
    {
        return new self(
            'no recepients has been provided. Use `addAddress` method once or multiple times to specify as many recipients information as needed'
        );
    }
}
