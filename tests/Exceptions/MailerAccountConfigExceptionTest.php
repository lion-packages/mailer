<?php

declare(strict_types=1);

/*
 * chore: apply PSR-12 style fixes from StyleCI
 */

namespace Tests\Exceptions;

use Lion\Mailer\Exceptions\MailerAccountConfigException;
use Lion\Test\Test;
use PHPUnit\Framework\Attributes\Test as Testing;

class MailerAccountConfigExceptionTest extends Test
{
    #[Testing]
    public function invalidSMTPEncryptionProtocol(): void
    {
        $this->expectException(MailerAccountConfigException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('The provided encryption protocol is invalid');

        throw MailerAccountConfigException::invalidSMTPEncryptionProtocol();
    }

    #[Testing]
    public function invalidMailerAccountType(): void
    {
        $this->expectException(MailerAccountConfigException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(
            'The provided mailer account type is invalid. The available account types are `phpmailer` and `symfony`'
        );

        throw MailerAccountConfigException::invalidMailerAccountType();
    }

    #[Testing]
    public function accountNotFound(): void
    {
        $this->expectException(MailerAccountConfigException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("The account `testing` could not be found");

        throw MailerAccountConfigException::accountNotFound('testing');
    }

    #[Testing]
    public function emptyAccountList(): void
    {
        $this->expectException(MailerAccountConfigException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(
            'The configuration array passed to the initialize method should contain at least on account'
        );

        throw MailerAccountConfigException::emptyAccountList();
    }

    #[Testing]
    public function invalidDefaultAccount(): void
    {
        $this->expectException(MailerAccountConfigException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(
            'No default account has been provided or the default account provided is invalid'
        );

        throw MailerAccountConfigException::invalidDefaultAccount();
    }
}
