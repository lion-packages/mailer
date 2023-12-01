<?php

declare(strict_types=1);

namespace Tests;

use LionMailer\AccountType;
use LionMailer\Exceptions\MailerAccountConfigException;
use LionMailer\Mailer;
use LionMailer\MailerAccountInterface;
use PHPUnit\Framework\TestCase;

class MailerTest extends TestCase
{
    private array $accounts;

    const PHPMAILER_TEST_ACCOUNT = [
        'name' => 'default',
        'type' => 'phpmailer',
        'host' => 'host',
        'username' => 'username',
        'password' => 'password',
        'port' => 495,
        'encryption' => 'tls',
        'debug' => false
    ];

    const SYMFONY_TEST_ACCOUNT = [
        'name' => 'support',
        'type' => 'symfony',
        'host' => 'host',
        'username' => 'username',
        'password' => 'password',
        'port' => 495,
        'encryption' => 'tls',
        'debug' => false
    ];

    public function setUp(): void
    {
        $this->accounts = [
            'default' => $this::PHPMAILER_TEST_ACCOUNT,
            'support' => $this::SYMFONY_TEST_ACCOUNT
        ];
    }

    public static function accountProvider(): array
    {
        return [
            [AccountType::PHPMailer, self::PHPMAILER_TEST_ACCOUNT],
            [AccountType::Symfony, self::SYMFONY_TEST_ACCOUNT],
        ];
    }

    public function testMailerClassWorks(): void
    {
        Mailer::initialize($this->accounts);

        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::account('default'));
        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::account('support'));
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

        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::account('default'));
    }

    public function testMailerThrowsExceptionWhenAccountIsNotFound(): void
    {
        Mailer::initialize($this->accounts);

        $this->expectException(MailerAccountConfigException::class);

        Mailer::account('invalid');
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
            'default' => [
                ...$this::PHPMAILER_TEST_ACCOUNT,
                'type' => 'invalid'
            ],
        ]);
    }
}
