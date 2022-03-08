<?php

namespace LionMailer;

use LionMailer\Attach;

class Mailer {

	private static array $classname;
	private static array $info;
	private static object $phpmailer;
	
	public function __construct() {
		// https://accounts.google.com/DisplayUnlockCaptcha
	}

	public static function init(array $options): void {
		if (isset($options['class'], $options['info'])) {
			self::$info = $options['info'];
			self::$classname = $options['class'];

			self::$phpmailer = new self::$classname['PHPMailer'](true);
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
			if (count($attach->getAddAttachment()) > 0) {
				$attachment = $attach->getAddAddress();
				isset($attachment[1]) ? self::$phpmailer->addAttachment($attachment[0], $attachment[1]) : self::$phpmailer->addAttachment($attachment[0]);
			}
		}

		self::$phpmailer->isHTML(true);
		self::$phpmailer->Subject = $attach->getSubject();
		self::$phpmailer->Body = $attach->getBody();
		self::$phpmailer->AltBody = $attach->getAltBody();
	}

	public static function send(Attach $attach): array {
		try {
			self::$phpmailer->SMTPDebug = isset(self::$info['debug']) ? self::$info['debug'] : self::$classname['SMTP']::DEBUG_SERVER;
			self::$phpmailer->isSMTP();
			self::$phpmailer->Host = self::$info['host'];
			self::$phpmailer->SMTPAuth = true;
			self::$phpmailer->Username = self::$info['email'];
			self::$phpmailer->Password = self::$info['password'];
			self::$phpmailer->SMTPSecure = self::$classname['PHPMailer']::ENCRYPTION_STARTTLS;
			self::$phpmailer->Port = self::$info['port'];
			self::addData($attach);
			self::$phpmailer->send();

			return ['status' => "success", 'message' => true];
		} catch (Exception $e) {
			return ['status' => "error", 'message' => $e->getMessage()];
		}
	}

}