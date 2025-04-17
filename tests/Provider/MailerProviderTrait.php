<?php

declare(strict_types=1);

/*
 * chore: apply PSR-12 style fixes from StyleCI
 */

namespace Tests\Provider;

use Lion\Mailer\AccountType;

trait MailerProviderTrait
{
    private const array PHPMAILER_TEST_ACCOUNT = [
        'name' => 'default',
        'type' => 'phpmailer',
        'host' => 'host',
        'username' => 'username',
        'password' => 'password',
        'port' => 495,
        'encryption' => 'tls',
        'debug' => false,
    ];

    private const array SYMFONY_TEST_ACCOUNT = [
        'name' => 'support',
        'type' => 'symfony',
        'host' => 'host',
        'username' => 'username',
        'password' => 'password',
        'port' => 495,
        'encryption' => 'tls',
        'debug' => false,
    ];

    /**
     * @return array<int, array{
     *     type: AccountType,
     *     config: array{
     *         name: string,
     *         type: string,
     *         host: string,
     *         username: string,
     *         password: string,
     *         port: int,
     *         encryption: bool|string,
     *         debug?: bool,
     *     }
     * }>
     */
    public static function accountProvider(): array
    {
        return [
            [
                'type' => AccountType::PHPMailer,
                'config' => self::PHPMAILER_TEST_ACCOUNT,
            ],
            [
                'type' => AccountType::Symfony,
                'config' => self::SYMFONY_TEST_ACCOUNT,
            ],
        ];
    }
}
