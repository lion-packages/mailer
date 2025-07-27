<?php

namespace LionMailer;

class Attach
{
    public function __construct(
        private ?array $addAddress = [],
        private ?array $addReplyTo = [],
        private ?string $addCC = null,
        private ?string $addBCC = null,
        private ?array $addAttachment = [],
        private ?string $subject = null,
        private ?string $body = null,
        private ?string $altBody = null
    ) {
    }

    public function getAddAddress()
    {
        return $this->addAddress;
    }

    public function setAddAddress($addAddress)
    {
        $this->addAddress = $addAddress;

        return $this;
    }

    public function getAddReplyTo()
    {
        return $this->addReplyTo;
    }

    public function setAddReplyTo($addReplyTo)
    {
        $this->addReplyTo = $addReplyTo;

        return $this;
    }

    public function getAddCC()
    {
        return $this->addCC;
    }

    public function setAddCC($addCC)
    {
        $this->addCC = $addCC;

        return $this;
    }

    public function getAddBCC()
    {
        return $this->addBCC;
    }

    public function setAddBCC($addBCC)
    {
        $this->addBCC = $addBCC;

        return $this;
    }

    public function getAddAttachment()
    {
        return $this->addAttachment;
    }

    public function setAddAttachment($addAttachment)
    {
        $this->addAttachment = $addAttachment;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function getAltBody()
    {
        return $this->altBody;
    }

    public function setAltBody($altBody)
    {
        $this->altBody = $altBody;

        return $this;
    }
}
