<?php

namespace LionMailer;

use PHPMailer\PHPMailer\{ PHPMailer, SMTP, Exception };
use LionMailer\Attach;

class Mailer {
	private static array $info;
	private static object $phpmailer;
	
	public function __construct() {
		// https://accounts.google.com/DisplayUnlockCaptcha
	}

	public static function init(array $options): void {
		if (isset($options['info'])) {
			self::$info = $options['info'];
			self::$phpmailer = new PHPMailer(true);
		}
	}

	private static function addData(Attach $attach): void {
		self::$phpmailer->setFrom(self::$info['email'], self::$info['user_name']);

		if ($attach->getAddAddress() != null) {
			if (count($attach->getAddAddress()) > 0) {
				$address = $attach->getAddAddress();
				isset($address[1]) ? self::$phpmailer->addAddress($address[0], $address[1]) : self::$phpmailer->addAddress($address[0]);
			}
		}

		if ($attach->getAddReplyTo() != null) {
			if (count($attach->getAddReplyTo()) > 0) {
				$reply = $attach->getAddReplyTo();
				self::$phpmailer->addReplyTo($reply[0], $reply[1]);
			}
		}

		if ($attach->getAddCC() != null) {
			self::$phpmailer->addCC($attach->getAddCC());
		}

		if ($attach->getAddBCC() != null) {
			self::$phpmailer->addBCC($attach->getAddBCC());
		}

		if ($attach->getAddAttachment() != null) {
			foreach ($attach->getAddAttachment() as $key => $file) {
				isset($file[1]) ? self::$phpmailer->addAttachment($file[0], $file[1]) : self::$phpmailer->addAttachment($file[0]);
			}
		}

		self::$phpmailer->isHTML(true);
		self::$phpmailer->Subject = $attach->getSubject();
		self::$phpmailer->Body = $attach->getBody();
		self::$phpmailer->AltBody = $attach->getAltBody();
	}

	public static function send(Attach $attach): array {
		try {
			self::$phpmailer->CharSet = 'UTF-8';
			self::$phpmailer->Encoding = 'base64';
			self::$phpmailer->SMTPDebug = isset(self::$info['debug']) ? self::$info['debug'] : SMTP::DEBUG_SERVER;
			self::$phpmailer->isSMTP();
			self::$phpmailer->Host = self::$info['host'];
			self::$phpmailer->SMTPAuth = true;
			self::$phpmailer->Username = self::$info['email'];
			self::$phpmailer->Password = self::$info['password'];
			self::$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			self::$phpmailer->Port = self::$info['port'];
			self::addData($attach);
			self::$phpmailer->send();

			return ['status' => "success", 'message' => 'The email has been sent successfully.'];
		} catch (Exception $e) {
			return ['status' => "error", 'message' => $e->getMessage()];
		}
	}

}