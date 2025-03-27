<?php

declare(strict_types=1);

namespace Tests;

use Lion\Mailer\Priority;
use Lion\Mailer\MailerAccountInterface;
use Lion\Mailer\Exceptions\EmptyBodyException;
use Lion\Mailer\MailerAccountConfig;
use Lion\Mailer\Exceptions\InvalidFromAddressException;
use Lion\Mailer\Exceptions\InvalidRecipientAddressException;
use Lion\Mailer\Exceptions\MailerAccountConfigException;
use Lion\Test\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Provider\MailerAccountsProviderTrait;

class MailerAccountsTest extends Test
{
    use MailerAccountsProviderTrait;

    private const array CONFIG = [
        'host' => HOST,
        'username' => 'username@examplfe.com',
        'password' => 'password',
        'port' => 1025,
        'encryption' => false,
        'debug' => false
    ];
    private const string TEST = 'test';
    private const string SUBJECT = 'Test Email';
    private const string FROM_EMAIL = 'from@example.com';
    private const string FROM_NAME = 'From Name';
    private const string ADDRESS_EMAIL = 'address@example.com';
    private const string ADDRESS_NAME = 'Adress Name';
    private const string ADDRESS_EMAIL_SECOND = 'address2@example.com';
    private const string ADDRESS_NAME_SECOND = 'Adress Name 2';
    private const string REPLY_TO_EMAIL = 'replyto@example.com';
    private const string REPLY_TO_NAME = 'Reply To Name';
    private const string REPLY_TO_EMAIL_SECOND = 'replyto2@example.com';
    private const string REPLY_TO_NAME_SECOND = 'Reply To Name 2';
    private const string ADD_CC_EMAIL = 'cc@example.com';
    private const string ADD_CC_NAME = 'CC Name';
    private const string ADD_CC_EMAIL_SECOND = 'cc2@example.com';
    private const string ADD_CC_NAME_SECOND = 'CC Name 2';
    private const string ADD_BCC_EMAIL = 'bcc@example.com';
    private const string ADD_BCC_NAME = 'BCC Name';
    private const string ADD_BCC_EMAIL_SECOND = 'bcc2@example.com';
    private const string ADD_BCC_NAME_SECOND = 'BCC Name 2';
    private const string BODY = 'Test Body';
    private const string ALT_BODY = 'Test Alt Body';

    #[DataProvider('mailerAccountProvider')]
    public function testCanBeInstatiated(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray(self::CONFIG));

        $this->assertInstanceOf(MailerAccountInterface::class, $mailer);
    }

    #[DataProvider('mailerAccountProvider')]
    public function testThrowsExceptionOnInvalidConfig(string $mailerService): void
    {
        $this->expectException(MailerAccountConfigException::class);

        new $mailerService(MailerAccountConfig::fromArray([
            ...self::CONFIG,
            'encryption' => self::TEST,
        ]));
    }

    #[DataProvider('mailerAccountProvider')]
    public function testCanSendEmail(string $mailerService): void
    {
        $mailer = (new $mailerService(MailerAccountConfig::fromArray(self::CONFIG)))
            ->subject(self::SUBJECT)
            ->from(self::FROM_EMAIL, self::FROM_NAME)
            ->addAddress(self::ADDRESS_EMAIL, self::ADDRESS_NAME)
            ->addAddress(self::ADDRESS_EMAIL_SECOND, self::ADDRESS_NAME_SECOND)
            ->addReplyTo(self::REPLY_TO_EMAIL, self::REPLY_TO_NAME)
            ->addReplyTo(self::REPLY_TO_EMAIL_SECOND, self::REPLY_TO_NAME_SECOND)
            ->addCC(self::ADD_CC_EMAIL, self::ADD_CC_NAME)
            ->addCC(self::ADD_CC_EMAIL_SECOND, self::ADD_CC_NAME_SECOND)
            ->addBCC(self::ADD_BCC_EMAIL, self::ADD_BCC_NAME)
            ->addBCC(self::ADD_BCC_EMAIL_SECOND, self::ADD_BCC_NAME_SECOND)
            ->body(self::BODY)
            ->altBody(self::ALT_BODY);

        $this->assertTrue($mailer->send());
    }

    #[DataProvider('mailerAccountProvider')]
    public function testThrowsExceptionWhenNoRecepientAddressIsProvided(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray(self::CONFIG));

        $this->expectException(InvalidRecipientAddressException::class);

        $mailer->subject(self::SUBJECT)
            ->from(self::FROM_EMAIL, self::FROM_NAME)
            ->body(self::BODY)
            ->send();
    }

    #[DataProvider('mailerAccountProvider')]
    public function testThrowsExceptionWhenNoFromAddressIsProvided(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray(self::CONFIG));

        $this->expectException(InvalidFromAddressException::class);

        $mailer->subject(self::SUBJECT)
            ->addAddress(self::ADDRESS_EMAIL, self::ADDRESS_NAME)
            ->body(self::BODY)
            ->send();
    }

    #[DataProvider('mailerAccountProvider')]
    public function testThrowsExceptionWhenNoBodyIsProvided(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray(self::CONFIG));

        $this->expectException(EmptyBodyException::class);

        $mailer
            ->subject(self::SUBJECT)
            ->from(self::FROM_EMAIL, self::FROM_NAME)
            ->addAddress(self::ADDRESS_EMAIL, self::ADDRESS_NAME)
            ->send();
    }

    #[DataProvider('mailerAccountProvider')]
    public function testPriority(string $mailerService): void
    {
        $mailer = (new $mailerService(MailerAccountConfig::fromArray(self::CONFIG)))
            ->subject('Test Priority')
            ->from(self::FROM_EMAIL, self::FROM_NAME)
            ->addAddress(self::ADDRESS_EMAIL, self::ADDRESS_NAME)
            ->body(self::BODY)
            ->priority(Priority::HIGH);

        $this->assertTrue($mailer->send());
    }

    #[DataProvider('mailerAccountProvider')]
    public function testReplyTo(string $mailerService): void
    {
        $mailer = (new $mailerService(MailerAccountConfig::fromArray(self::CONFIG)))
            ->subject('Test Reply To')
            ->from(self::FROM_EMAIL, self::FROM_NAME)
            ->addAddress(self::ADDRESS_EMAIL, self::ADDRESS_NAME)
            ->body(self::BODY)
            ->addReplyTo(self::REPLY_TO_EMAIL, self::REPLY_TO_NAME);

        $this->assertTrue($mailer->send());
    }

    #[DataProvider('mailerAccountProvider')]
    public function testCC(string $mailerService): void
    {
        $mailer = (new $mailerService(MailerAccountConfig::fromArray(self::CONFIG)))
            ->subject('Test CC')
            ->from(self::FROM_EMAIL, self::FROM_NAME)
            ->addAddress(self::ADDRESS_EMAIL, self::ADDRESS_NAME)
            ->body(self::BODY)
            ->addCC(self::ADD_CC_EMAIL, self::ADD_CC_NAME);

        $this->assertTrue($mailer->send());
    }

    #[DataProvider('mailerAccountProvider')]
    public function testBCC(string $mailerService): void
    {
        $mailer = (new $mailerService(MailerAccountConfig::fromArray(self::CONFIG)))
            ->subject('Test BCC')
            ->from(self::FROM_EMAIL, self::FROM_NAME)
            ->addAddress(self::ADDRESS_EMAIL, self::ADDRESS_NAME)
            ->body(self::BODY)
            ->addCC(self::ADD_CC_EMAIL, self::ADD_CC_NAME)
            ->addBCC(self::ADD_BCC_EMAIL, self::ADD_BCC_NAME);

        $this->assertTrue($mailer->send());
    }

    #[DataProvider('mailerAccountProvider')]
    public function testAddAttachment(string $mailerService): void
    {
        $mailer = (new $mailerService(MailerAccountConfig::fromArray(self::CONFIG)))
            ->subject('Test Attatchment')
            ->from(self::FROM_EMAIL, self::FROM_NAME)
            ->addAddress(self::ADDRESS_EMAIL, self::ADDRESS_NAME)
            ->body(self::BODY)
            ->addAttachment(__DIR__ . '/support/test_file.txt', 'Test File');

        $this->assertTrue($mailer->send());
    }

    #[DataProvider('mailerAccountProvider')]
    public function testAddEmbeddedImage(string $mailerService): void
    {
        $mailer = (new $mailerService(MailerAccountConfig::fromArray(self::CONFIG)))
            ->subject('Test Embedded Image')
            ->from(self::FROM_EMAIL, self::FROM_NAME)
            ->addAddress(self::ADDRESS_EMAIL, self::ADDRESS_NAME)
            ->body(
                <<<HTML
                <img src="cid:image1" alt="Embedded Image">
                HTML
            )
            ->addEmbeddedImage(__DIR__ . '/support/test_image.jpg', 'image1', 'image.jpg');

        $this->assertTrue($mailer->send());
    }

    #[DataProvider('mailerAccountProvider')]
    public function testMethodsWithOptionalNameArguments(string $mailerService): void
    {
        $mailer = (new $mailerService(MailerAccountConfig::fromArray(self::CONFIG)))
            ->subject('Test Optional Name Arguments')
            ->from(self::FROM_EMAIL)
            ->addAddress(self::ADDRESS_EMAIL)
            ->addReplyTo(self::REPLY_TO_EMAIL)
            ->addCC(self::ADD_CC_EMAIL)
            ->addBCC(self::ADD_BCC_EMAIL)
            ->body(self::BODY);

        $this->assertTrue($mailer->send());
    }
}
