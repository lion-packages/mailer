<?php

declare(strict_types=1);

namespace Tests\Provider;

use LionMailer\Accounts\PHPMailerAccount;
use LionMailer\Accounts\SymfonyMailerAccount;

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
