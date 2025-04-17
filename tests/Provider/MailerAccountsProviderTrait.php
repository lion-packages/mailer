<?php

declare(strict_types=1);

/*
 * chore: apply PSR-12 style fixes from StyleCI
 */

namespace Tests\Provider;

use Lion\Mailer\Accounts\PHPMailerAccount;
use Lion\Mailer\Accounts\SymfonyMailerAccount;

trait MailerAccountsProviderTrait
{
    /**
     * @return array<int, array{
     *     mailerService: class-string
     * }>
     */
    public static function mailerAccountProvider(): array
    {
        return [
            [
                'mailerService' => PHPMailerAccount::class,
            ],
            [
                'mailerService' => SymfonyMailerAccount::class,
            ],
        ];
    }
}
