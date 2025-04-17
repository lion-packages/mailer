<?php

declare(strict_types=1);

/*
 * chore: apply PSR-12 style fixes from StyleCI
 */

namespace Lion\Mailer\Exceptions;

use Exception;

/**
 * Exception for emails with empty body
 *
 * @package Lion\Mailer\Exceptions
 */
class EmptyBodyException extends Exception
{
    /**
     * Returns an object of type Exception with an error message
     *
     * @return self
     */
    public static function make(): self
    {
        return new self('message body has not been specified. Use the `body` method to specify the message body', 500);
    }
}
