<?php

declare(strict_types=1);

namespace LionMailer;

use LionMailer\Exceptions\MailerAccountConfigException;

class MailerAccountConfig
{
    public function __construct(
        public readonly string $host,
        public readonly string $username,
        public readonly string $password,
        public readonly int $port = 465,
        public readonly string $encryption = 'tls',
        public readonly bool $debug = false,
    ) {
        if (!in_array($encryption, ['tls', 'ssl', 'false'])) {
            throw MailerAccountConfigException::invalidSMTPEncryptionProtocol();
        }
    }

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
