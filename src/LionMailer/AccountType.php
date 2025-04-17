<?php

declare(strict_types=1);

/*
 * chore: apply PSR-12 style fixes from StyleCI
 */

namespace Lion\Mailer;

use Lion\Mailer\Accounts\PHPMailerAccount;
use Lion\Mailer\Accounts\SymfonyMailerAccount;

/**
 * Enumeration for service configuration
 *
 * @package Lion\Mailer
 */
enum AccountType: string
{
    /**
     * [Defines the PHPMailer service]
     */
    case PHPMailer = 'phpmailer';

    /**
     * [Defines the SymfonyMailer service]
     */
    case Symfony = 'symfony';

    /**
     * Returns the defined service
     *
     * @return string
     */
    public function getClassName(): string
    {
        return match ($this) {
            AccountType::PHPMailer => PHPMailerAccount::class,
            AccountType::Symfony => SymfonyMailerAccount::class,
        };
    }
}
