<?php

declare(strict_types=1);

namespace Tests\Provider;

use Lion\Mailer\Accounts\PHPMailerAccount;
use Lion\Mailer\Accounts\SymfonyMailerAccount;

trait MailerAccountsProviderTrait
{
    public static function mailerAccountProvider(): array
    {
        return [
            [PHPMailerAccount::class],
            [SymfonyMailerAccount::class],
        ];
    }
}
