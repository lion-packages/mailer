<?php

declare(strict_types=1);

namespace Lion\Mailer\Exceptions;

use Exception;

class MailerAccountConfigException extends Exception
{
    public static function invalidSMTPEncryptionProtocol(): self
    {
        return new self('the provided encryption protocol is invalid');
    }

    public static function invalidMailerAccountType(): self
    {
        return new self(
            'the provided mailer account type is invalid. The available account types are `phpmailer` and `symfony`'
        );
    }

    public static function accountNotFound(string $name): self
    {
        return new self("the account `{$name}` could not be found.");
    }

    public static function emptyAccountList(): self
    {
        return new self('the configuration array passed to the initialize method should contain at least on account');
    }

    public static function invalidDefaultAccount(): self
    {
        return new self('no default account has been provided or the default account provided is invalid');
    }
}
