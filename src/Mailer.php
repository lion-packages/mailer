<?php

declare(strict_types=1);

namespace LionMailer;

use LionMailer\MailerAccountConfig;
use LionMailer\MailerAccountInterface;
use LionMailer\Accounts\PHPMailerAccount;
use LionMailer\Accounts\SymfonyMailerAccount;
use LionMailer\Exceptions\MailerAccountConfigException;

class Mailer
{
    private static array $accounts;

    private static string $default;

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

    public static function default(): MailerAccountInterface
    {
        return self::account(self::$default);
    }

    public static function setDefault(string $name): void
    {
        var_dump($name);

        if (!isset(self::$accounts[$name])) {
            throw MailerAccountConfigException::accountNotFound($name);
        }

        self::$default = $name;
    }

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
