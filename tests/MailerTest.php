<?php

declare(strict_types=1);

namespace Tests;

use Lion\Mailer\AccountType;
use Lion\Mailer\Exceptions\MailerAccountConfigException;
use Lion\Mailer\Mailer;
use Lion\Mailer\MailerAccountConfig;
use Lion\Mailer\MailerAccountInterface;
use Lion\Test\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test as Testing;
use ReflectionException;
use Tests\Provider\MailerProviderTrait;

class MailerTest extends Test
{
    use MailerProviderTrait;

    private const string INVALID = 'invalid';
    private const string DEFAULT_ = 'default';
    private const string SUPPORT = 'support';

    private array $accounts;

    public function setUp(): void
    {
        $this->accounts = [
            self::DEFAULT_ => $this::PHPMAILER_TEST_ACCOUNT,
            self::SUPPORT => $this::SYMFONY_TEST_ACCOUNT
        ];
    }

    /**
     * @throws MailerAccountConfigException
     */
    public function testMailerClassWorks(): void
    {
        Mailer::initialize($this->accounts);

        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::account(self::DEFAULT_));
        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::account(self::SUPPORT));
    }

    /**
     * @param $type AccountType
     * @param array{
     *      name: string,
     *      type: string,
     *      host: string,
     *      username: string,
     *      password: string,
     *      port: int,
     *      encryption: string,
     *      debug?: bool
     *  } $config
     *
     * @throws MailerAccountConfigException
     * @throws ReflectionException
     */
    #[Testing]
    #[DataProvider('accountProvider')]
    public function addAccount(AccountType $type, array $config): void
    {
        $mailer = new Mailer();

        $mailer->addAccount($config['name'], $config);

        $this->initReflection($mailer);

        $accounts = $this->getPrivateProperty('accounts');

        $this->assertIsArray($accounts);
        $this->assertNotEmpty($accounts);
        $this->assertArrayHasKey($config['name'], $accounts);
        $this->assertIsArray($accounts[$config['name']]);
        $this->assertNotEmpty($accounts[$config['name']]);
        $this->assertArrayHasKey('name', $accounts[$config['name']]);
        $this->assertArrayHasKey('type', $accounts[$config['name']]);
        $this->assertArrayHasKey('config', $accounts[$config['name']]);
        $this->assertIsString($accounts[$config['name']]['name']);
        $this->assertIsString($accounts[$config['name']]['type']);
        $this->assertIsObject($accounts[$config['name']]['config']);
        $this->assertInstanceOf(MailerAccountConfig::class, $accounts[$config['name']]['config']);
    }

    /**
     * @param $type AccountType
     * @param array{
     *     name: string,
     *     type: string,
     *     host: string,
     *     username: string,
     *     password: string,
     *     port: int,
     *     encryption: string,
     *     debug?: bool
     * } $config
     *
     * @throws MailerAccountConfigException
     */
    #[Testing]
    #[DataProvider('accountProvider')]
    public function account(AccountType $type, array $config): void
    {
        Mailer::addAccount($config['name'], $config);

        $this->assertInstanceOf($type->getClassName(), Mailer::account($config['name']));
    }

    /**
     * @throws MailerAccountConfigException
     */
    public function testMailerReturnsAccountObject(): void
    {
        Mailer::initialize($this->accounts);

        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::account(self::DEFAULT_));
    }

    /**
     * @throws MailerAccountConfigException
     */
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

    /**
     * @throws MailerAccountConfigException
     */
    public function testDefaultAccountIsSetWhenCallingInitializeMethod(): void
    {
        Mailer::initialize($this->accounts);

        $this->assertInstanceOf(MailerAccountInterface::class, Mailer::default());
    }


    /**
     * @throws MailerAccountConfigException
     */
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
                'type' => self::INVALID,
            ],
        ]);
    }
}
