<?php

declare(strict_types=1);

namespace Tests;

use Lion\Mailer\AccountType;
use Lion\Mailer\Exceptions\MailerAccountConfigException;
use Lion\Mailer\Mailer;
use Lion\Mailer\MailerAccountInterface;
use PHPUnit\Framework\TestCase;
use Tests\Provider\MailerProviderTrait;

class MailerTest extends TestCase
{
    use MailerProviderTrait;

    const INVALID = 'invalid';
    const DEFAULT_ = 'default';
    const SUPPORT = 'support';

    private array $accounts;

    public function setUp(): void
    {
        $this->accounts = [
            self::DEFAULT_ => $this::PHPMAILER_TEST_ACCOUNT,
            self::SUPPORT => $this::SYMFONY_TEST_ACCOUNT
        ];
    }

    public function testMailerClassWorks(): void
    {
        Mailer::initialize($this->accounts);

        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::account(self::DEFAULT_));
        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::account(self::SUPPORT));
    }

    /**
     * @dataProvider accountProvider
     */
    public function testMailerReturnsCorrespondingAccountType(AccountType $type, array $config): void
    {
        Mailer::addAccount($config['name'], $config);

        $this->assertInstanceOf($type->getClassName(), Mailer::account($config['name']));
    }

    public function testMailerReturnsAccountObject(): void
    {
        Mailer::initialize($this->accounts);

        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::account(self::DEFAULT_));
    }

    public function testMailerThrowsExceptionWhenAccountIsNotFound(): void
    {
        Mailer::initialize($this->accounts);

        $this->expectException(MailerAccountConfigException::class);

        Mailer::account(self::INVALID);
    }

    public function testThrowsExceptionWhenProvidedEmptyAccountsArray(): void
    {
        $this->expectException(MailerAccountConfigException::class);

        Mailer::initialize([]);
    }

    public function testDefaultAccountIsSetWhenCallingInitializeMethod(): void
    {
        Mailer::initialize($this->accounts);

        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::default());
    }


    public function testWhenProvidingOneAccountThisIsSetAsDefault(): void
    {
        Mailer::initialize(['test' => self::SYMFONY_TEST_ACCOUNT]);

        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::default());
    }

    public function testThrowsExceptionOnInvalidAccountType(): void
    {
        $this->expectException(MailerAccountConfigException::class);

        Mailer::initialize([
            self::DEFAULT_ => [
                ...$this::PHPMAILER_TEST_ACCOUNT,
                'type' => self::INVALID
            ],
        ]);
    }
}
