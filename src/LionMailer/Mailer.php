<?php

namespace LionMailer;

use LionMailer\DataMailer\Data;
use PHPMailer\PHPMailer\{Exception, PHPMailer, SMTP};

class Mailer extends Data
{
    private static array $info;
    private static PHPMailer $phpmailer;

    public function __construct()
    {
    }

    public static function init(array $options): void
    {
        if (isset($options['info'])) {
            self::$info = $options['info'];
            self::$phpmailer = new PHPMailer(true);
        }
    }

    private static function response(string $status, ?string $message = null, array $data = []): object
    {
        return (object) [
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ];
    }

    public static function newInfo(string $subject, string $body, string $altBody): object
    {
        return (object) [
            'subject' => $subject,
            'body'    => $body,
            'altBody' => $altBody,
        ];
    }

    public static function newGroupInfo(
        array $addReplyTo,
        ?string $addCC = null,
        ?string $addBCC = null,
        ?array $addAttachment = [],
        string $subject,
        string $body,
        string $altBody
    ): object {
        return (object) [
            'addReplyTo'    => $addReplyTo,
            'addCC'         => $addCC,
            'addBCC'        => $addBCC,
            'addAttachment' => $addAttachment,
            'subject'       => $subject,
            'body'          => $body,
            'altBody'       => $altBody,
        ];
    }

    public static function send(object $attach, object $newInfo): object
    {
        try {
            self::$phpmailer->CharSet = 'UTF-8';
            self::$phpmailer->Encoding = 'base64';
            self::$phpmailer->SMTPDebug = isset(self::$info['debug']) ? self::$info['debug'] : SMTP::DEBUG_SERVER;
            self::$phpmailer->isSMTP();
            self::$phpmailer->Host = self::$info['host'];
            self::$phpmailer->SMTPAuth = true;
            self::$phpmailer->Username = self::$info['email'];
            self::$phpmailer->Password = self::$info['password'];
            self::$phpmailer->SMTPSecure = !self::$info['encryption'] ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            self::$phpmailer->Port = self::$info['port'];
            self::$phpmailer->setFrom(self::$info['email'], self::$info['user_name']);
            self::$phpmailer = self::addData(self::$phpmailer, $attach);
            self::$phpmailer->isHTML(true);
            self::$phpmailer->Subject = $newInfo->subject;
            self::$phpmailer->Body = $newInfo->body;
            self::$phpmailer->AltBody = $newInfo->altBody;
            self::$phpmailer->send();

            return self::response('success', 'The email has been sent successfully.');
        } catch (Exception $e) {
            return self::response('error', $e->getMessage());
        }
    }

    public static function sendGroup(object $attachs, object $newGroupInfo): object
    {
        try {
            self::$phpmailer->CharSet = 'UTF-8';
            self::$phpmailer->Encoding = 'base64';
            self::$phpmailer->SMTPDebug = isset(self::$info['debug']) ? self::$info['debug'] : SMTP::DEBUG_SERVER;
            self::$phpmailer->isSMTP();
            self::$phpmailer->Host = self::$info['host'];
            self::$phpmailer->SMTPAuth = true;
            self::$phpmailer->Username = self::$info['email'];
            self::$phpmailer->Password = self::$info['password'];
            self::$phpmailer->SMTPSecure = !self::$info['encryption'] ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            self::$phpmailer->Port = self::$info['port'];
            self::$phpmailer->setFrom(self::$info['email'], self::$info['user_name']);
            self::$phpmailer = self::addGroupData(self::$phpmailer, $attachs, $newGroupInfo);
            self::$phpmailer->isHTML(true);
            self::$phpmailer->Subject = $newGroupInfo->subject;
            self::$phpmailer->Body = $newGroupInfo->body;
            self::$phpmailer->AltBody = $newGroupInfo->altBody;
            self::$phpmailer->send();

            return self::response('success', 'The email has been sent successfully.');
        } catch (Exception $e) {
            return self::response('error', $e->getMessage());
        }
    }
}
