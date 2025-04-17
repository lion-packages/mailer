<?php

declare(strict_types=1);

namespace Lion\Mailer;

use Lion\Mailer\Exceptions\MailerAccountConfigException;

/**
 * Create email account settings
 *
 * @package Lion\Mailer
 */
class MailerAccountConfig
{
    /**
     * Specify a tls encryption
     *
     * @const ENCRYPTION_STARTTLS
     */
    public const string ENCRYPTION_STARTTLS = 'tls';

    /**
     * [Specify a ssl encryption]
     *
     * @const ENCRYPTION_SMTPS
     */
    public const string ENCRYPTION_SMTPS = 'ssl';

    /**
     * [List of available encryption's]
     *
     * @const ENCRYPTION
     */
    private const array ENCRYPTION = [
        self::ENCRYPTION_STARTTLS,
        self::ENCRYPTION_SMTPS,
        false,
    ];

    /**
     * Class constructor
     *
     * @param string $host [Service host]
     * @param string $username [Account username]
     * @param string $password [Account password]
     * @param int $port [Service port]
     * @param bool|string $encryption [Account encryption]
     * @param bool $debug [Service debug]
     *
     * @throws MailerAccountConfigException
     *
     * @infection-ignore-all
     */
    public function __construct(
        public readonly string $host,
        public readonly string $username,
        public readonly string $password,
        public readonly int $port = 465,
        public readonly bool|string $encryption = self::ENCRYPTION_STARTTLS,
        public readonly bool $debug = false,
    ) {
        if (!in_array($encryption, self::ENCRYPTION, true)) {
            throw MailerAccountConfigException::invalidSMTPEncryptionProtocol();
        }
    }

    /**
     * Create the configuration object from an array
     *
     * @param array{
     *     name: string,
     *     type: string,
     *     host: string,
     *     username: string,
     *     password: string,
     *     port: int,
     *     encryption: bool|string,
     *     debug?: bool
     * } $config [Configuration data array]
     *
     * @return self
     *
     * @throws MailerAccountConfigException
     */
    public static function fromArray(array $config): self
    {
        return new self(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['port'],
            $config['encryption'],
            empty($config['debug']) ? false : $config['debug'],
        );
    }
}
