<?php

namespace LionMailer;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private static Mailer $mailer;
    private static PHPMailer $phpMailer;

    private static array $config = [];
    private static string $from_email = '';
    private static string $from_name = '';
    private static string $address_email = '';
    private static string $address_name = '';
    private static string $reply_email = '';
    private static string $reply_name = '';
    private static bool $active_cc = false;
    private static string $cc = '';
    private static bool $active_bcc = false;
    private static string $bcc = '';
    private static bool $active_attachment = false;
    private static string $path = '';
    private static string $file_name = '';
    private static bool $isHtml = true;
    private static string $subject = '';
    private static string $body = '';
    private static string $alt_body = '';
    private static array $address_list = [];

    public static function init(array $config): void
    {
        self::$config = $config;
        self::$mailer = new Mailer();
        self::$phpMailer = new PHPMailer(true);
    }

    public static function header(string $name, string $value): Mailer
    {
        self::$phpMailer->addCustomHeader($name, $value);

        return self::$mailer;
    }

    public static function multiple(): Mailer
    {
        self::$address_list = func_get_args();

        return self::$mailer;
    }

    public static function embeddedImage(string $path, string $cid): Mailer
    {
        self::$phpMailer->addEmbeddedImage($path, $cid);

        return self::$mailer;
    }

    public static function from(string $from_email, string $from_name = ''): Mailer
    {
        self::$from_email = $from_email;
        self::$from_name = $from_name;

        return self::$mailer;
    }

    public static function address(string $address_email, string $address_name = ''): Mailer
    {
        self::$address_email = $address_email;
        self::$address_name = $address_name;

        return self::$mailer;
    }

    public static function replyTo(string $reply_email, string $reply_name = ''): Mailer
    {
        self::$reply_email = $reply_email;
        self::$reply_name = $reply_name;

        return self::$mailer;
    }

    public static function cc(string $cc): Mailer
    {
        self::$active_cc = true;
        self::$cc = $cc;

        return self::$mailer;
    }

    public static function bcc(string $bcc): Mailer
    {
        self::$active_bcc = true;
        self::$bcc = $bcc;

        return self::$mailer;
    }

    public static function attachment(string $path, string $file_name = ''): Mailer
    {
        self::$active_attachment = true;
        self::$path = $path;
        self::$file_name = $file_name;

        return self::$mailer;
    }

    public static function html(bool $isHtml = true): Mailer
    {
        self::$isHtml = $isHtml;

        return self::$mailer;
    }

    public static function subject(string $subject): Mailer
    {
        self::$subject = $subject;

        return self::$mailer;
    }

    public static function body(mixed $body): Mailer
    {
        self::$body = $body;

        return self::$mailer;
    }

    public static function altBody(mixed $alt_body): Mailer
    {
        self::$alt_body = $alt_body;

        return self::$mailer;
    }

    public static function send(): object
    {
        try {
            self::$phpMailer->SMTPDebug = self::$config['debug'];
            self::$phpMailer->isSMTP();
            self::$phpMailer->Host = self::$config['host'];
            self::$phpMailer->SMTPAuth = true;
            self::$phpMailer->Username = self::$config['username'];
            self::$phpMailer->Password = self::$config['password'];
            self::$phpMailer->SMTPSecure = self::$config['encryption'];
            self::$phpMailer->Port = self::$config['port'];

            self::$phpMailer->setFrom(
                self::$from_email === '' ? self::$config['username'] : self::$from_email,
                self::$from_name
            );

            if (count(self::$address_list) > 0) {
                foreach (self::$address_list as $key => $address) {
                    self::$phpMailer->addAddress($address);
                }
            } else {
                self::$phpMailer->addAddress(self::$address_email, self::$address_name);
            }

            self::$phpMailer->addReplyTo(self::$reply_email, self::$reply_name);

            if (self::$active_cc) {
                self::$active_cc = false;
                self::$phpMailer->addCC(self::$cc);
            }

            if (self::$active_bcc) {
                self::$active_bcc = false;
                self::$phpMailer->addBCC(self::$bcc);
            }

            if (self::$active_attachment) {
                self::$active_attachment = false;
                self::$phpMailer->addAttachment(self::$path, self::$file_name);
            }

            self::$phpMailer->isHTML(self::$isHtml);
            self::$phpMailer->Subject = self::$subject;
            self::$phpMailer->Body = self::$body;
            self::$phpMailer->AltBody = self::$alt_body;
            self::$phpMailer->send();

            return (object) [
                'status'  => 'success',
                'message' => 'the message has been sent',
            ];
        } catch (Exception $e) {
            return (object) [
                'status'  => 'error',
                'message' => 'Message could not be sent',
                'data'    => (object) [
                    'exception' => $e->getMessage(),
                ],
            ];
        }
    }
}
