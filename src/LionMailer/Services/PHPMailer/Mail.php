<?php

namespace LionMailer\Services\PHPMailer;

use LionMailer\SettingsMailServices;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail extends SettingsMailServices {

	private static PHPMailer $phpMailer;

	public static function init(array $accounts): void {
		if (self::$phpMail === null) {
			self::$phpMail = new Mail();
			self::$phpMailer = new PHPMailer(true);
		}

		self::$accounts = $accounts;
		self::$active_account = self::$accounts['default'];
	}

	public static function highPriority(): Mail {
		self::$phpMailer->Priority = 1;
		return self::$phpMail;
	}

	public static function regularPriority(): Mail {
		self::$phpMailer->Priority = 3;
		return self::$phpMail;
	}

	public static function lowPriority(): Mail {
		self::$phpMailer->Priority = 5;
		return self::$phpMail;
	}

	public static function account(string $account): Mail {
		self::$active_account = $account;
		return self::$phpMail;
	}

	public static function header(string $name, string $value): Mail {
		self::$phpMailer->addCustomHeader($name, $value);
		return self::$phpMail;
	}

	public static function multiple(): Mail {
		foreach (func_get_args() as $key => $address) {
			if (is_array($address)) {
				self::$phpMailer->addAddress($address[0], $address[1]);
			} else {
				self::$phpMailer->addAddress($address);
			}
		}

		return self::$phpMail;
	}

	public static function embeddedImage(string $path, string $cid, string $name = ""): Mail {
		self::$phpMailer->addEmbeddedImage($path, $cid, $name);
		return self::$phpMail;
	}

	public static function address(string $address_email, string $address_name = ""): Mail {
		self::$phpMailer->addAddress($address_email, $address_name);
		return self::$phpMail;
	}

	public static function replyTo(string $reply_email, string $reply_name = ""): Mail {
		self::$phpMailer->addReplyTo($reply_email, $reply_name);
		return self::$phpMail;
	}

	public static function cc(): Mail {
		foreach (func_get_args() as $key => $cc) {
			if (is_array($cc)) {
				self::$phpMailer->addCC($cc[0], $cc[1]);
			} else {
				self::$phpMailer->addCC($cc);
			}
		}

		return self::$phpMail;
	}

	public static function bcc(string $bcc): Mail {
		foreach (func_get_args() as $key => $cc) {
			if (is_array($cc)) {
				self::$phpMailer->addBCC($cc[0], $cc[1]);
			} else {
				self::$phpMailer->addBCC($cc);
			}
		}

		return self::$phpMail;
	}

	public static function attachment(string $path, string $file_name = ""): Mail {
		self::$phpMailer->addAttachment($path, $file_name);
		return self::$phpMail;
	}

	public static function subject(string $subject): Mail {
		self::$phpMailer->Subject = $subject;
		return self::$phpMail;
	}

	public static function body(mixed $body): Mail {
		self::$phpMailer->Body = $body;
		return self::$phpMail;
	}

	public static function altBody(mixed $alt_body): Mail {
		self::$phpMailer->AltBody = $alt_body;
		return self::$phpMail;
	}

	public static function send(bool $isHtml = true): object {
		$account = self::$accounts['accounts'][self::$active_account];

		if (!in_array("phpmailer", $account['services'])) {
			return (object) ['status' => 'mail-error', 'message' => 'phpmailer service is not enabled for this account'];
		}

		try {
			self::$phpMailer->SMTPDebug = $account['debug'];
			self::$phpMailer->isSMTP();
			self::$phpMailer->Host = $account['host'];
			self::$phpMailer->SMTPAuth = true;
			self::$phpMailer->Username = $account['account'];
			self::$phpMailer->Password = $account['password'];
			self::$phpMailer->SMTPSecure = $account['encryption'];
			self::$phpMailer->Port = $account['port'];
			self::$phpMailer->setFrom($account['account'], $account['name']);
			self::$phpMailer->isHTML($isHtml);
			self::$phpMailer->send();
			self::clean();
			return (object) ['status' => 'success', 'message' => 'the message has been sent'];
		} catch (Exception $e) {
			return (object) ['status' => 'mail-error', 'message' => $e->getMessage()];
		}
	}

}