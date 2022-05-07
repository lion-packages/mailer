<?php

namespace LionMailer;

use PHPMailer\PHPMailer\{ PHPMailer, SMTP, Exception };

class Mailer {

	private static array $info;
	private static PHPMailer $phpmailer;
	private static ?object $attach = null;
	
	public function __construct() {

	}

	public static function init(array $options): void {
		if (isset($options['info'])) {
			self::$info = $options['info'];
			self::$phpmailer = new PHPMailer(true);
		}
	}

	private static function response(string $status, ?string $message = null, array $data = []): object {
		return (object) ['status' => $status, 'message' => $message, 'data' => $data];
	}

	public static function newInfo(string $subject, string $body, string $altBody): object {
		return (object) ['subject' => $subject, 'body' => $body, 'altBody' => $altBody];
	}

	public static function newAttach(?array $addAddress = [], ?array $addReplyTo = [], ?string $addCC = null, ?string $addBCC = null, ?array $addAttachment = []): object {
		if (self::$attach === null) {
			self::$attach = (object) [
				'addAddress' => $addAddress,
				'addReplyTo' => $addReplyTo,
				'addCC' => $addCC,
				'addBCC' => $addBCC,
				'addAttachment' => $addAttachment
			];
		} else {
			self::$attach->addAddress = $addAddress;
			self::$attach->addReplyTo = $addReplyTo;
			self::$attach->addCC = $addCC;
			self::$attach->addBCC = $addBCC;
			self::$attach->addAttachment = $addAttachment;
		}

		return self::$attach;
	}

	private static function addData(object $attach): void {
		self::$phpmailer->setFrom(self::$info['email'], self::$info['user_name']);

		if ($attach->addAddress != null) {
			$address = $attach->addAddress;

			if (isset($address[1])) {
				self::$phpmailer->addAddress(trim($address[0]), trim($address[1]));
			} else {
				self::$phpmailer->addAddress(trim($address[0]));
			}
		}

		if ($attach->addReplyTo != null) {
			$reply = $attach->addReplyTo;
			self::$phpmailer->addReplyTo(trim($reply[0]), trim($reply[1]));
		}

		if ($attach->addCC != null) {
			self::$phpmailer->addCC(trim($attach->addCC));
		}

		if ($attach->addBCC != null) {
			self::$phpmailer->addBCC(trim($attach->addBCC));
		}

		if ($attach->addAttachment != null) {
			foreach ($attach->addAttachment as $key => $file) {
				isset($file[1]) ? self::$phpmailer->addAttachment($file[0], $file[1]) : self::$phpmailer->addAttachment($file[0]);
			}
		}
	}

	public static function send(object $attach, object $newInfo): object {
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

			self::addData($attach);
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

}