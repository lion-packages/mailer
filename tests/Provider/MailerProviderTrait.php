<?php

declare(strict_types=1);

namespace Tests\Provider;

use LionMailer\AccountType;

trait MailerProviderTrait
{
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

    public static function accountProvider(): array
    {
        return [
            [AccountType::PHPMailer, self::PHPMAILER_TEST_ACCOUNT],
            [AccountType::Symfony, self::SYMFONY_TEST_ACCOUNT],
        ];
    }
}
