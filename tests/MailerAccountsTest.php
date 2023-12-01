<?php

declare(strict_types=1);

namespace Tests;

use LionMailer\Priority;
use PHPUnit\Framework\TestCase;
use LionMailer\MailerAccountInterface;
use LionMailer\Accounts\PHPMailerAccount;
use LionMailer\Accounts\SymfonyMailerAccount;
use LionMailer\Exceptions\EmptyBodyException;
use LionMailer\MailerAccountConfig;
use LionMailer\Exceptions\InvalidFromAddressException;
use LionMailer\Exceptions\InvalidRecipientAddressException;
use LionMailer\Exceptions\MailerAccountConfigException;

class MailerAccountsTest extends TestCase
{
    protected array $config;

    public function setUp(): void
    {
        $this->config = [
            'host' => 'mailhog',
            'username' => 'username@examplfe.com',
            'password' => 'password',
            'port' => 1025,
            'encryption' => 'false',
            'debug' => false
        ];
    }

    public static function mailerAccountProvider(): array
    {
        return [
            [PHPMailerAccount::class],
            [SymfonyMailerAccount::class],
        ];
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testCanBeInstatiated(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $this->assertInstanceOf(MailerAccountInterface::class, $mailer);
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testThrowsExceptionOnInvalidConfig(string $mailerService): void
    {
        $this->expectException(MailerAccountConfigException::class);

        new $mailerService(MailerAccountConfig::fromArray([
            ...$this->config,
            'encryption' => 'test'
        ]));
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testCanSendEmail(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $result = $mailer
            ->subject('Test Email')
            ->from('from@example.com', 'From Name')
            ->addAddress('address@example.com', 'Adress Name')
            ->addAddress('address2@example.com', 'Adress Name 2')
            ->addReplyTo('replyto@example.com', 'Reply To Name')
            ->addReplyTo('replyto2@example.com', 'Reply To Name 2')
            ->addCC('cc@example.com', 'CC Name')
            ->addCC('cc2@example.com', 'CC Name 2')
            ->addBCC('bcc@example.com', 'BCC Name')
            ->addBCC('bcc2@example.com', 'BCC Name 2')
            ->body('Test Body')
            ->altBody('Test Alt Body')
            ->send();

        $this->assertTrue($result);
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testThrowsExceptionWhenNoRecepientAddressIsProvided(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $this->expectException(InvalidRecipientAddressException::class);

        $mailer->subject('Test Email')
            ->from('from@example.com', 'From Test Name')
            ->body('Test Body')
            ->send();
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testThrowsExceptionWhenNoFromAddressIsProvided(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $this->expectException(InvalidFromAddressException::class);

        $mailer->subject('Test Email')
            ->addAddress('address@example.com', 'Adress Name')
            ->body('Test Body')
            ->send();
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testThrowsExceptionWhenNoBodyIsProvided(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $this->expectException(EmptyBodyException::class);

        $mailer
            ->subject('Test Email')
            ->from('from@example.com', 'From Name')
            ->addAddress('address@example.com', 'Adress Name')
            ->send();
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testPriority(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $mailer
            ->subject('Test Priority')
            ->from('from@example.com', 'From Name')
            ->addAddress('address@example.com', 'Adress Name')
            ->body('Test Body')
            ->priority(Priority::HIGH);

        $this->assertTrue($mailer->send());
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testReplyTo(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $mailer
            ->subject('Test Reply To')
            ->from('from@example.com', 'From Name')
            ->addAddress('address@example.com', 'Adress Name')
            ->body('Test Body')
            ->addReplyTo('replyto@example.com', 'Reply To');

        $this->assertTrue($mailer->send());
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testCC(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $mailer
            ->subject('Test CC')
            ->from('from@example.com', 'From Name')
            ->addAddress('address@example.com', 'Adress Name')
            ->body('Test Body')
            ->addCC('cc@example.com', 'CC Name');

        $this->assertTrue($mailer->send());
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testBCC(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $mailer
            ->subject('Test BCC')
            ->from('from@example.com', 'From Name')
            ->addAddress('address@example.com', 'Adress Name')
            ->body('Test Body')
            ->addCC('cc@example.com', 'CC Name')
            ->addBCC('bcc@example.com', 'BCC Name');

        $this->assertTrue($mailer->send());
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testaddAttachment(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $mailer
            ->subject('Test Attatchment')
            ->from('from@example.com', 'From Name')
            ->addAddress('address@example.com', 'Adress Name')
            ->body('Test Body')
            ->addAttachment(__DIR__ . '/support/test_file.txt', 'Test File');

        $this->assertTrue($mailer->send());
    }

    /**
     * @dataProvider mailerAccountProvider
     */
    public function testaddEmbeddedImage(string $mailerService): void
    {
        $mailer = new $mailerService(MailerAccountConfig::fromArray($this->config));

        $mailer
            ->subject('Test Embedded Image')
            ->from('from@example.com', 'From Name')
            ->addAddress('address@example.com', 'Adress Name')
            ->body('<img src="cid:image1" alt="Embedded Image">')
            ->addEmbeddedImage(__DIR__ . '/support/test_image.jpg', 'image1', 'image.jpg');

        $this->assertTrue($mailer->send());
    }
}
