<?php

namespace LionMailer;

use LionMailer\Services\PHPMailer\Mail as PHPMail;
use LionMailer\Services\Symfony\Mail as SymfMail;

class MailService {

	public static function run(array $accounts): object {
		if (!isset($accounts['default'])) {
			return (object) ['status' => "mail-error", 'message' => "the service requires a default account"];
		}

		if (in_array($accounts['default'], ["", null])) {
			return (object) ['status' => "mail-error", 'message' => "the service requires a default account"];
		}

		$default_account = $accounts['accounts'][$accounts['default']];

		if (strtolower($default_account['service']) === "phpmailer") {
			PHPMail::init($accounts);
		} elseif (strtolower($default_account['service']) === "symfony") {
			SymfMail::init($accounts);
		} else {
			return (object) ['status' => "mail-error", 'message' => "the service does not exist"];
		}

		return (object) ['status' => "success", 'message' => "service enabled successfully"];
	}

}