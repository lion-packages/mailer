<?php

namespace LionMailer;

use LionMailer\Services\PHPMailer\Mail as PHPMail;
use LionMailer\Services\Symfony\Mail as SymfMail;

class SettingsMailServices {

	protected static ?PHPMail $phpMail = null;
	protected static ?SymfMail $symfMail = null;

	protected static array $accounts = [];
	protected static string $active_account = "";

	protected static function clean() {
		self::$active_account = self::$accounts['default'];
	}

}