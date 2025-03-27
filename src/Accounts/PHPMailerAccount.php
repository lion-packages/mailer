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
 */
class PHPMailerAccount implements MailerAccountInterface
{
    /**
     * [PHPMailer - PHP email creation and transport class]
     *
     * @var PHPMailer $mailer
     */
    private PHPMailer $mailer;

    /**
     * {@inheritDoc}
     */
    public function __construct(protected MailerAccountConfig $config)
    {
        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();

        $this->mailer->isHTML();

        $this->mailer->SMTPAuth = (bool) $config->encryption;

        /** @phpstan-ignore-next-line */
        $this->mailer->SMTPSecure = !$config->encryption ? false : $config->encryption;

        $this->mailer->SMTPDebug = 0;

        $this->mailer->Host = $config->host;

        $this->mailer->Username = $config->username;

        $this->mailer->Password = $config->password;

        $this->mailer->Port = $config->port;
    }

    /**
     * {@inheritDoc}
     */
    public function priority(Priority $priority): MailerAccountInterface
    {
        $this->mailer->Priority = $priority->value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function subject(string $subject): MailerAccountInterface
    {
        $this->mailer->Subject = $subject;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function from(string $address, string $name = ''): MailerAccountInterface
    {
        $this->mailer->setFrom($address, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addAddress(string $address, string $name = ''): MailerAccountInterface
    {
        $this->mailer->addAddress($address, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addReplyTo(string $address, string $name = ''): MailerAccountInterface
    {
        $this->mailer->addReplyTo($address, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addCC(string $address, string $name = ''): MailerAccountInterface
    {
        $this->mailer->addCC($address, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addBCC(string $address, string $name = ''): MailerAccountInterface
    {
        $this->mailer->addBCC($address, $name);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function addAttachment(string $path, ?string $fileName = null): MailerAccountInterface
    {
        $this->mailer->addAttachment($path, (null === $fileName ? '' : $fileName));

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
        $this->mailer->addEmbeddedImage($path, $cid, $name, 'base64', (null === $mimeType ? '' : $mimeType));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function body(string $body): MailerAccountInterface
    {
        $this->mailer->Body = $body;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function altBody(string $altBody): MailerAccountInterface
    {
        $this->mailer->AltBody = $altBody;

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
        if (!$this->mailer->From) {
            throw InvalidFromAddressException::emptyFromAddress();
        }

        if (!$this->mailer->getAllRecipientAddresses()) {
            throw InvalidRecipientAddressException::emptyRecipientsList();
        }

        if (!$this->mailer->Body) {
            throw EmptyBodyException::make();
        }

        return $this->mailer->send();
    }
}
