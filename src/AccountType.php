<?php

declare(strict_types=1);

namespace LionMailer;

use LionMailer\Accounts\PHPMailerAccount;
use LionMailer\Accounts\SymfonyMailerAccount;

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
