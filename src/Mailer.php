<?php

declare(strict_types=1);

namespace Lion\Mailer;

use Lion\Mailer\MailerAccountConfig;
use Lion\Mailer\MailerAccountInterface;
use Lion\Mailer\Accounts\PHPMailerAccount;
use Lion\Mailer\Accounts\SymfonyMailerAccount;
use Lion\Mailer\Exceptions\MailerAccountConfigException;

/**
 * Initializes all services and their defined configurations
 *
 * @package Lion\Mailer
 */
class Mailer
{
    /**
     * [List of all defined accounts]
     *
     * @var array $accounts
     */
    private static array $accounts;

    /**
     * [Default account]
     *
     * @var string $default
     */
    private static string $default;

    /**
     * Initialize account settings
     *
     * @param  array $accounts [List of all defined accounts]
     * @param  string $default [Default account]
     *
     * @return void
     */
    public static function initialize(array $accounts, string $default = 'default'): void
    {
        if (count($accounts) <= 0) {
            throw MailerAccountConfigException::emptyAccountList();
        }

        foreach ($accounts as $account => $config) {
            self::addAccount($account, $config);

            if ($account === $default) {
                self::setDefault($account);
            }
        }

        if (count($accounts) === 1) {
            self::setDefault(array_key_first($accounts));
        }

        if (!isset(self::$default)) {
            throw MailerAccountConfigException::invalidDefaultAccount();
        }
    }

    /**
     * Returns a service defined by default
     *
     * @return MailerAccountInterface
     */
    public static function default(): MailerAccountInterface
    {
        return self::account(self::$default);
    }

    /**
     * Change the default account to the defined account
     *
     * @param string $name [Default account]
     *
     * @return void
     */
    public static function setDefault(string $name): void
    {
        if (!isset(self::$accounts[$name])) {
            throw MailerAccountConfigException::accountNotFound($name);
        }

        self::$default = $name;
    }

    /**
     * Add an account
     *
     * @param string $name [Account name]
     * @param array $config [Configuration data for the account]
     *
     * @return void
     */
    public static function addAccount(string $name, array $config): void
    {
        if (!in_array($config["type"], ['phpmailer', 'symfony'])) {
            throw MailerAccountConfigException::invalidMailerAccountType();
        }

        self::$accounts[$name] = [
            'name' => $config['name'],
            'type' => $config['type'],
            'config' => MailerAccountConfig::fromArray($config),
        ];
    }

    /**
     * Get an account
     *
     * @param  string $name [Account name]
     *
     * @return MailerAccountInterface
     */
    public static function account(string $name): MailerAccountInterface
    {
        $account = self::$accounts[$name] ?? ['type' => null];

        return match ($account['type']) {
            'phpmailer' => new PHPMailerAccount($account['config']),
            'symfony' => new SymfonyMailerAccount($account['config']),
            default => throw MailerAccountConfigException::accountNotFound($name),
        };
    }
}
