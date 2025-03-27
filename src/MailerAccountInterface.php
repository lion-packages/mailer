<?php

declare(strict_types=1);

namespace Lion\Mailer;

/**
 * Defines the configuration of native functions to send emails
 *
 * @package Lion\Mailer
 */
interface MailerAccountInterface
{
    /**
     * Class constructor
     *
     * @param MailerAccountConfig $config [Configuration object for PHPMailer
     * service]
     */
    public function __construct(MailerAccountConfig $config);

    /**
     * Defines the priority level
     *
     * @param Priority $priority [Priority level]
     *
     * @return MailerAccountInterface
     */
    public function priority(Priority $priority): MailerAccountInterface;

    /**
     * Mail subject
     *
     * @param string $subject [Subject]
     *
     * @return MailerAccountInterface
     */
    public function subject(string $subject): MailerAccountInterface;

    /**
     * Define who sends the email
     *
     * @param string $address [Mail account]
     * @param string $name [Account name]
     *
     * @return MailerAccountInterface
     */
    public function from(string $address, string $name = ''): MailerAccountInterface;

    /**
     * Defines the destination account
     *
     * @param string $address [Destination account]
     * @param string $name [Account name]
     *
     * @return MailerAccountInterface
     */
    public function addAddress(string $address, string $name = ''): MailerAccountInterface;

    /**
     * Account that answers email
     *
     * @param string $address [Account that responds]
     * @param string $name [Account name]
     *
     * @return MailerAccountInterface
     */
    public function addReplyTo(string $address, string $name = ''): MailerAccountInterface;

    /**
     * Send a copy of the message to other accounts
     *
     * @param string $address [Destination account]
     * @param string $name [Account name]
     *
     * @return MailerAccountInterface
     */
    public function addCC(string $address, string $name = ''): MailerAccountInterface;

    /**
     * Send a blind copy of the message to other accounts
     *
     * @param string $address [Destination account]
     * @param string $name [Account name]
     *
     * @return MailerAccountInterface
     */
    public function addBCC(string $address, string $name = ''): MailerAccountInterface;

    /**
     * Attaches defined files
     *
     * @param string $path [File path]
     * @param string|null $fileName [File name]
     *
     * @return MailerAccountInterface
     */
    public function addAttachment(string $path, ?string $fileName = null): MailerAccountInterface;

    /**
     * Allows you to embed images directly in the body of the message
     *
     * @param string $path [File path]
     * @param string $cid [Content ID of the attachment; Use this to reference
     * the content when using an embedded image in HTML]
     * @param string $name [Overrides the attachment filename]
     * @param string|null $mimeType [File MIME type (by default mapped from the
     * `$path` filename's extension)]
     *
     * @return MailerAccountInterface
     */
    public function addEmbeddedImage(
        string $path,
        string $cid,
        string $name = '',
        ?string $mimeType = null
    ): MailerAccountInterface;

    /**
     * Defines the body of the email
     *
     * @param string $body [Email body]
     *
     * @return MailerAccountInterface
     */
    public function body(string $body): MailerAccountInterface;

    /**
     * Provides a plain text version of the message body to be displayed in
     * email clients that do not support HTML or that have the HTML display
     * option disabled
     *
     * @param string $altBody [Email body]
     *
     * @return MailerAccountInterface
     */
    public function altBody(string $altBody): MailerAccountInterface;

    /**
     * Send the email after you have configured all the message details
     *
     * @return bool
     */
    public function send(): bool;
}
