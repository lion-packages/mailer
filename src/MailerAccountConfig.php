<?php

declare(strict_types=1);

namespace Lion\Mailer;

use Lion\Mailer\Exceptions\MailerAccountConfigException;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Create email account settings
 *
 * @property string $host [Service host]
 * @property string $username [Account username]
 * @property string $password [Account password]
 * @property int $port [Service port]
 * @property string $encryption [Account encryption]
 * @property bool $debug [Service debug]
 *
 * @package Lion\Mailer
 */
class MailerAccountConfig
{
    /**
     * Class constructor
     *
     * @param string $host [Service host]
     * @param string $username [Account username]
     * @param string $password [Account password]
     * @param int $port [Service port]
     * @param string $encryption [Account encryption]
     * @param bool $debug [Service debug]
     */
    public function __construct(
        public readonly string $host,
        public readonly string $username,
        public readonly string $password,
        public readonly int $port = 465,
        public readonly bool|string $encryption = 'tls',
        public readonly bool $debug = false,
    ) {
        if (!in_array($encryption, [PHPMailer::ENCRYPTION_SMTPS, PHPMailer::ENCRYPTION_STARTTLS, false], true)) {
            throw MailerAccountConfigException::invalidSMTPEncryptionProtocol();
        }
    }

    /**
     * Create the configuration object from an array
     *
     * @param array $config [Configuration data array]
     *
     * @return self
     */
    public static function fromArray(array $config): self
    {
        return new self(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['port'],
            $config['encryption'],
            $config['debug']
        );
    }
}
