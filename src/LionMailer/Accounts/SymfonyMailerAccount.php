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
 * @package Lion\Mailer\Accounts
 *
 * @infection-ignore-all
 */
class SymfonyMailerAccount implements MailerAccountInterface
{
    /**
     * [Object of Email class]
     *
     * @var Email $service
     */
    private Email $service;

    /**
     * [Defines the DNS configuration for the symfony service]
     *
     * @var string $dns
     */
    private string $dns;

    /**
     * {@inheritDoc}
     */
    public function __construct(protected MailerAccountConfig $config)
    {
        $this->service = new Email();

        $this->dns = "smtp://{$config->username}:{$config->password}@{$config->host}:{$config->port}";

        $this->dns .= "?encryption={$config->encryption}";

        $this->dns .= "&debug=" . ($config->debug ? 'true' : 'false');
    }

    /**
     * {@inheritDoc}
     */
    public function priority(Priority $priority): MailerAccountInterface
    {
        $this->service->priority($priority->value);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function from(string $address, string $name = ''): MailerAccountInterface
    {
        $address = new Address($address, $name);

        $this->service->from($address);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function subject(string $subject): MailerAccountInterface
    {
        $this->service->subject($subject);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addAddress(string $address, string $name = ''): MailerAccountInterface
    {
        $address = new Address($address, $name);

        $this->service->addTo($address);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addReplyTo(string $address, string $name = ''): MailerAccountInterface
    {
        $address = new Address($address, $name);

        $this->service->addReplyTo($address);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addCC(string $address, string $name = ''): MailerAccountInterface
    {
        $address = new Address($address, $name);

        $this->service->addCc($address);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addBCC(string $address, string $name = ''): MailerAccountInterface
    {
        $address = new Address($address, $name);

        $this->service->addBcc($address);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addAttachment(string $path, ?string $fileName = null): MailerAccountInterface
    {
        $file = new File($path, $fileName);

        $dataPart = new DataPart($file);

        $this->service->addPart($dataPart);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addEmbeddedImage(
        string $path,
        string $cid,
        ?string $name = null,
        ?string $mimeType = null
    ): MailerAccountInterface {
        $file = new File($path);

        $dataPart = new DataPart($file, $cid, $mimeType)
            ->asInline();

        $this->service->addPart($dataPart);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function body(string $body): MailerAccountInterface
    {
        $this->service->html($body);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function altBody(string $altBody): MailerAccountInterface
    {
        $this->service->text($altBody);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @throws TransportExceptionInterface
     * @throws InvalidFromAddressException
     * @throws InvalidRecipientAddressException
     * @throws EmptyBodyException
     */
    public function send(): bool
    {
        if (!$this->service->getFrom()) {
            throw InvalidFromAddressException::emptyFromAddress();
        }

        if (!$this->service->getTo()) {
            throw InvalidRecipientAddressException::emptyRecipientsList();
        }

        if (!$this->service->getHtmlBody() && !$this->service->getTextBody()) {
            throw EmptyBodyException::make();
        }

        $mailer = new Mailer(Transport::fromDsn($this->dns));

        $mailer->send($this->service);

        return true;
    }
}
