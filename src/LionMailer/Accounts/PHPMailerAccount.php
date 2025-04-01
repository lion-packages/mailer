<?php

declare(strict_types=1);

namespace Lion\Mailer\Accounts;

use Lion\Mailer\Exceptions\EmptyBodyException;
use Lion\Mailer\Exceptions\InvalidFromAddressException;
use Lion\Mailer\Exceptions\InvalidRecipientAddressException;
use Lion\Mailer\MailerAccountConfig;
use Lion\Mailer\MailerAccountInterface;
use Lion\Mailer\Priority;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Service to send emails with PHPMailer
 *
 * @package Lion\Mailer\Accounts
 *
 * @infection-ignore-all
 */
class PHPMailerAccount implements MailerAccountInterface
{
    /**
     * [PHPMailer - PHP email creation and transport class]
     *
     * @var PHPMailer $service
     */
    private PHPMailer $service;

    /**
     * {@inheritDoc}
     */
    public function __construct(protected MailerAccountConfig $config)
    {
        $this->service = new PHPMailer(true);

        $this->service->isSMTP();

        $this->service->isHTML();

        $this->service->SMTPAuth = (bool) $config->encryption;

        /** @phpstan-ignore-next-line */
        $this->service->SMTPSecure = !$config->encryption ? false : $config->encryption;

        $this->service->SMTPDebug = 0;

        $this->service->Host = $config->host;

        $this->service->Username = $config->username;

        $this->service->Password = $config->password;

        $this->service->Port = $config->port;
    }

    /**
     * {@inheritDoc}
     */
    public function priority(Priority $priority): MailerAccountInterface
    {
        $this->service->Priority = $priority->value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function subject(string $subject): MailerAccountInterface
    {
        $this->service->Subject = $subject;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function from(string $address, string $name = ''): MailerAccountInterface
    {
        $this->service->setFrom($address, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addAddress(string $address, string $name = ''): MailerAccountInterface
    {
        $this->service->addAddress($address, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addReplyTo(string $address, string $name = ''): MailerAccountInterface
    {
        $this->service->addReplyTo($address, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addCC(string $address, string $name = ''): MailerAccountInterface
    {
        $this->service->addCC($address, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addBCC(string $address, string $name = ''): MailerAccountInterface
    {
        $this->service->addBCC($address, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addAttachment(string $path, ?string $fileName = null): MailerAccountInterface
    {
        $this->service->addAttachment($path, (null === $fileName ? '' : $fileName));

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addEmbeddedImage(
        string $path,
        string $cid,
        string $name = '',
        ?string $mimeType = null
    ): MailerAccountInterface {
        $this->service->addEmbeddedImage($path, $cid, $name, 'base64', (null === $mimeType ? '' : $mimeType));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function body(string $body): MailerAccountInterface
    {
        $this->service->Body = $body;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function altBody(string $altBody): MailerAccountInterface
    {
        $this->service->AltBody = $altBody;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     * @throws InvalidFromAddressException
     * @throws InvalidRecipientAddressException
     * @throws EmptyBodyException
     */
    public function send(): bool
    {
        if (!$this->service->From) {
            throw InvalidFromAddressException::emptyFromAddress();
        }

        if (!$this->service->getAllRecipientAddresses()) {
            throw InvalidRecipientAddressException::emptyRecipientsList();
        }

        if (!$this->service->Body) {
            throw EmptyBodyException::make();
        }

        return $this->service->send();
    }
}
