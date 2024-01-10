<?php

declare(strict_types=1);

namespace Lion\Mailer;

use Lion\Mailer\Accounts\PHPMailerAccount;
use Lion\Mailer\Accounts\SymfonyMailerAccount;

enum AccountType: string
{
    case PHPMailer = "phpmailer";
    case Symfony = "symfony";

    public function getClassName(): string
    {
        return match ($this) {
            AccountType::PHPMailer => PHPMailerAccount::class,
            AccountType::Symfony => SymfonyMailerAccount::class,
        };
    }
}
