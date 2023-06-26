<?php

namespace LionMailer\Services\Symfony;

use LionMailer\SettingsMailServices;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

class Mail extends SettingsMailServices {

	private static Email $email;

	public static function init(array $accounts): void {
		if (self::$symfMail === null) {
			self::$symfMail = new Mail();
			self::$email = new Email();
		}

		self::$accounts = $accounts;
		self::$active_account = self::$accounts['default'];
	}

	public static function highestPriority(): Mail {
		self::$email->priority(Email::PRIORITY_HIGHEST);
		return self::$symfMail;
	}

	public static function highPriority(): Mail {
		self::$email->priority(Email::PRIORITY_HIGH);
		return self::$symfMail;
	}

	public static function normalPriority(): Mail {
		self::$email->priority(Email::PRIORITY_NORMAL);
		return self::$symfMail;
	}

	public static function lowPriority(): Mail {
		self::$email->priority(Email::PRIORITY_LOW);
		return self::$symfMail;
	}

	public static function lowestPriority(): Mail {
		self::$email->priority(Email::PRIORITY_LOWEST);
		return self::$symfMail;
	}

	public static function multiple(): Mail {
		self::$email->to(...array_map(function($account) {
			if (is_array($account)) {
				return new Address($account[0], $account[1]);
			} else {
				return $account;
			}
		}, func_get_args()));

		return self::$symfMail;
	}

	public static function embeddedImage(string $path, string $cid, string $mime): Mail {
		self::$email->addPart((
			new DataPart(
				new File($path),
				$cid,
				$mime
			)
		)->asInline());

		return self::$symfMail;
	}

	public static function address(string $account, string $name = ""): Mail {
		if (in_array($name, [null, ""])) {
			self::$email->to($account);
		} else {
			self::$email->to(new Address($account, $name));
		}

		return self::$symfMail;
	}

	public static function multipleReplyTo(): Mail {
		self::$email->getHeaders()->addMailboxListHeader('Reply-To', array_map(function($reply_to) {
			if (is_array($reply_to)) {
				return new Address($reply_to[0], $reply_to[1]);
			} else {
				return $reply_to;
			}
		}, func_get_args()));

		return self::$symfMail;
	}

	public static function replyTo(string $reply_to, string $name = ""): Mail {
		if (in_array($name, [null, ""])) {
			self::$email->replyTo($reply_to);
		} else {
			self::$email->replyTo(new Address($reply_to, $name));
		}

		return self::$symfMail;
	}

	public static function cc(): Mail {
		self::$email->cc(...array_map(function($account) {
			if (is_array($account)) {
				return new Address($account[0], $account[1]);
			} else {
				return $account;
			}
		}, func_get_args()));

		return self::$symfMail;
	}

	public static function bcc(): Mail {
		self::$email->bcc(...array_map(function($account) {
			if (is_array($account)) {
				return new Address($account[0], $account[1]);
			} else {
				return $account;
			}
		}, func_get_args()));

		return self::$symfMail;
	}

	public static function attachment(): Mail {
		foreach (func_get_args() as $key => $file) {
			if (is_array($file)) {
				self::$email->addPart(new DataPart(new File($file[0], $file[1])));
			} else {
				self::$email->addPart(new DataPart(new File($file)));
			}
		}

		return self::$symfMail;
	}

	public static function subject(string $subject): Mail {
		self::$email->subject($subject);
		return self::$symfMail;
	}

	public static function body(string $body): Mail {
		self::$email->html($body);
		return self::$symfMail;
	}

	public static function altBody(string $alt_body): Mail {
		self::$email->text($alt_body);
		return self::$symfMail;
	}

	public static function account(string $account): Mail {
		self::$active_account = $account;
		return self::$symfMail;
	}

	public static function send(): object {
		$account = self::$accounts['accounts'][self::$active_account];

		if (!in_array("symfony", $account['services'])) {
			return (object) ['status' => 'mail-error', 'message' => 'phpmailer service is not enabled for this account'];
		}

		try {
			$auth = "{$account['account']}:{$account['password']}";
			$host = "{$account['host']}:{$account['port']}";
			$mailer = new Mailer(Transport::fromDsn("smtp://{$auth}@{$host}"));
			self::$email->from(new Address($account['account'], $account['name']));
			$mailer->send(self::$email);
			self::clean();
			return (object) ['status' => 'success', 'message' => 'the message has been sent'];
		} catch (\Throwable $e) {
			return (object) ['status' => 'mail-error', 'message' => $e->getMessage()];
		}
	}

}