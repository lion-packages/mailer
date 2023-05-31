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

		if (!isset($accounts['accounts'][$accounts['default']])) {
			return (object) ['status' => "mail-error", 'message' => "the default account does not exist"];
		}

		$default_account = $accounts['accounts'][$accounts['default']];

		if (!is_array($default_account['services'])) {
			return (object) ['status' => "mail-error", 'message' => "the service does not exist"];
		}

		if (count($default_account['services']) === 0) {
			return (object) ['status' => "mail-error", 'message' => "the service does not exist"];
		}

		PHPMail::init($accounts);
		SymfMail::init($accounts);
		return (object) ['status' => "success", 'message' => "service enabled successfully"];
	}

}