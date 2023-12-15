<?php

declare(strict_types=1);

namespace LionMailer;

use LionMailer\MailerAccountConfig;
use LionMailer\Priority;

interface MailerAccountInterface
{
    public function __construct(MailerAccountConfig $config);

    public function priority(Priority $priority): MailerAccountInterface;

    public function subject(string $subject): MailerAccountInterface;

    public function from(string $address, ?string $name = null): MailerAccountInterface;

    public function addAddress(string $address, ?string $name = null): MailerAccountInterface;

    public function addReplyTo(string $address, ?string $name = null): MailerAccountInterface;

    public function addCC(string $address, ?string $name = null): MailerAccountInterface;

    public function addBCC(string $address, ?string $name = null): MailerAccountInterface;

    public function addAttachment(string $path, ?string $fileName = null): MailerAccountInterface;

    public function addEmbeddedImage(
        string $path,
        string $cid,
        ?string $name = null,
        ?string $mimeType = null
    ): MailerAccountInterface;

    public function body(string $body): MailerAccountInterface;

    public function altBody(string $altBody): MailerAccountInterface;

    public function send(): bool;
}
