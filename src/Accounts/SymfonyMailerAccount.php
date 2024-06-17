<?php

declare(strict_types=1);

namespace Lion\Mailer\Accounts;

use Lion\Mailer\Exceptions\EmptyBodyException;
use Lion\Mailer\Exceptions\InvalidFromAddressException;
use Lion\Mailer\Exceptions\InvalidRecipientAddressException;
use Lion\Mailer\MailerAccountConfig;
use Lion\Mailer\MailerAccountInterface;
use Lion\Mailer\Priority;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

/**
 * Service to send emails with SymfonyMailer
 *
 * @property Email $email [Object of Email class]
 * @property string $dns [Defines the DNS configuration for the symfony service]
 *
 * @package Lion\Mailer\Accounts
 */
class SymfonyMailerAccount implements MailerAccountInterface
{
    /**
     * [Object of Email class]
     *
     * @var Email $email
     */
    private Email $email;

    /**
     * [Defines the DNS configuration for the symfony service]
     *
     * @var string $dns
     */
    private string $dns;

    /**
     * {@inheritdoc}
     */
    public function __construct(protected MailerAccountConfig $config)
    {
        $this->email = new Email();

        $this->dns = "smtp://$config->username:$config->password@$config->host:$config->port";

        $this->dns .= "?encryption=$config->encryption";

        $this->dns .= "&debug=" . ($config->debug ? 'true' : 'false');
    }

    /**
     * {@inheritdoc}
     */
    public function priority(Priority $priority): MailerAccountInterface
    {
        $this->email->priority($priority->value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function from(string $address, string $name = ''): MailerAccountInterface
    {
        $this->email->from(new Address($address, $name));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subject(string $subject): MailerAccountInterface
    {
        $this->email->subject($subject);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addAddress(string $address, string $name = ''): MailerAccountInterface
    {
        $this->email->addTo(new Address($address, $name));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addReplyTo(string $address, string $name = ''): MailerAccountInterface
    {
        $this->email->addReplyTo(new Address($address, $name));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addCC(string $address, string $name = ''): MailerAccountInterface
    {
        $this->email->addCc(new Address($address, $name));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addBCC(string $address, string $name = ''): MailerAccountInterface
    {
        $this->email->addBcc(new Address($address, $name));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addAttachment(string $path, ?string $fileName = null): MailerAccountInterface
    {
        $this->email->addPart(new DataPart(new File($path, $fileName)));

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
        $this->email->addPart(
            (new DataPart(new File($path), $cid, $mimeType))
                ->asInline()
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function body(string $body): MailerAccountInterface
    {
        $this->email->html($body);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function altBody(string $altBody): MailerAccountInterface
    {
        $this->email->text($altBody);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws TransportExceptionInterface
     * @throws InvalidFromAddressException
     * @throws InvalidRecipientAddressException
     * @throws EmptyBodyException
     */
    public function send(): bool
    {
        if (!$this->email->getFrom()) {
            throw InvalidFromAddressException::emptyFromAddress();
        }

        if (!$this->email->getTo()) {
            throw InvalidRecipientAddressException::emptyRecipientsList();
        }

        if (!$this->email->getHtmlBody() && !$this->email->getTextBody()) {
            throw EmptyBodyException::make();
        }

        $mailer = new Mailer(Transport::fromDsn($this->dns));

        $mailer->send($this->email);

        return true;
    }
}
