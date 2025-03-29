<?php

declare(strict_types=1);

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
        return new self('the provided encryption protocol is invalid');
    }

    /**
     * Returns an object of type Exception with an error message
     *
     * @return self
     */
    public static function invalidMailerAccountType(): self
    {
        return new self(
            'the provided mailer account type is invalid. The available account types are `phpmailer` and `symfony`'
        );
    }

    /**
     * Returns an object of type Exception with an error message
     *
     * @param  string $name [Configured account name]
     *
     * @return self
     */
    public static function accountNotFound(string $name): self
    {
        return new self("the account `{$name}` could not be found.");
    }

    /**
     * Returns an object of type Exception with an error message
     *
     * @return self
     */
    public static function emptyAccountList(): self
    {
        return new self('the configuration array passed to the initialize method should contain at least on account');
    }

    /**
     * Returns an object of type Exception with an error message
     *
     * @return self
     */
    public static function invalidDefaultAccount(): self
    {
        return new self('no default account has been provided or the default account provided is invalid');
    }
}
