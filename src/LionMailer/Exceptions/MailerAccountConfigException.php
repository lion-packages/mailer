<?php

declare(strict_types=1);

/*
 * chore: apply PSR-12 style fixes from StyleCI
 */

namespace Lion\Mailer\Exceptions;

use Exception;

/**
 * Defines the Exception type objects for the mail configuration
 *
 * @package Lion\Mailer\Exceptions
 */
class MailerAccountConfigException extends Exception
{
    /**
     * Returns an object of type Exception with an error message
     *
     * @return self
     */
    public static function invalidSMTPEncryptionProtocol(): self
    {
        return new self('The provided encryption protocol is invalid', 500);
    }

    /**
     * Returns an object of type Exception with an error message
     *
     * @return self
     */
    public static function invalidMailerAccountType(): self
    {
        return new self(
            'The provided mailer account type is invalid. The available account types are `phpmailer` and `symfony`',
            500
        );
    }

    /**
     * Returns an object of type Exception with an error message
     *
     * @param string $name [Configured account name]
     *
     * @return self
     */
    public static function accountNotFound(string $name): self
    {
        return new self("The account `{$name}` could not be found", 500);
    }

    /**
     * Returns an object of type Exception with an error message
     *
     * @return self
     */
    public static function emptyAccountList(): self
    {
        return new self(
            'The configuration array passed to the initialize method should contain at least on account',
            500
        );
    }

    /**
     * Returns an object of type Exception with an error message
     *
     * @return self
     */
    public static function invalidDefaultAccount(): self
    {
        return new self('No default account has been provided or the default account provided is invalid', 500);
    }
}
