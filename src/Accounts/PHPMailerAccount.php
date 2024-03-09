<?php

declare(strict_types=1);

namespace Lion\Mailer\Accounts;

use Lion\Mailer\Priority;
use PHPMailer\PHPMailer\PHPMailer;
use Lion\Mailer\MailerAccountInterface;
use Lion\Mailer\Exceptions\EmptyBodyException;
use Lion\Mailer\MailerAccountConfig;
use Lion\Mailer\Exceptions\InvalidFromAddressException;
use Lion\Mailer\Exceptions\InvalidRecipientAddressException;

/**
 * Service to send emails with PHPMailer
 *
 * @package Lion\Mailer\Accounts
 */
class PHPMailerAccount implements MailerAccountInterface
{
    /**
     * [Object of PHPMailer class]
     *
     * @var PHPMailer $mailer
     */
    private PHPMailer $mailer;

    /**
     * {@inheritdoc}
     */
    public function __construct(protected MailerAccountConfig $config)
    {
        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();

        $this->mailer->isHTML(true);

        $this->mailer->SMTPAuth = true;

        $this->mailer->SMTPDebug = 0;

        $this->mailer->Host = $config->host;

        $this->mailer->Username = $config->username;

        $this->mailer->Password = $config->password;

        $this->mailer->Port = $config->port;

        $this->mailer->SMTPSecure = $config->encryption;
    }

    /**
     * {@inheritdoc}
     */
    public function priority(Priority $priority): MailerAccountInterface
    {
        $this->mailer->Priority = $priority->value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subject(string $subject): MailerAccountInterface
    {
        $this->mailer->Subject = $subject;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function from(string $address, string $name = ''): MailerAccountInterface
    {
        $this->mailer->setFrom($address, $name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addAddress(string $address, string $name = ''): MailerAccountInterface
    {
        $this->mailer->addAddress($address, $name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addReplyTo(string $address, string $name = ''): MailerAccountInterface
    {
        $this->mailer->addReplyTo($address, $name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addCC(string $address, string $name = ''): MailerAccountInterface
    {
        $this->mailer->addCC($address, $name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addBCC(string $address, string $name = ''): MailerAccountInterface
    {
        $this->mailer->addBCC($address, $name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addAttachment(string $path, ?string $fileName = null): MailerAccountInterface
    {
        $this->mailer->addAttachment($path, $fileName);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addEmbeddedImage(
        string $path,
        string $cid,
        ?string $name = null,
        ?string $mimeType = null
    ): MailerAccountInterface {
        $this->mailer->addEmbeddedImage($path, $cid, $name, PHPMailer::ENCODING_BASE64, $mimeType);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function body(string $body): MailerAccountInterface
    {
        $this->mailer->Body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function altBody(string $altBody): MailerAccountInterface
    {
        $this->mailer->AltBody = $altBody;

        return $this;
    }

    /**
     * {@inheritdoc}
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
