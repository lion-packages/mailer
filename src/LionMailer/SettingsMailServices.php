<?php

namespace LionMailer;

use LionMailer\Services\PHPMailer\Mail as PHPMail;

class SettingsMailServices {

	protected static ?PHPMail $phpMail = null;

	protected static array $accounts = [];
	protected static string $active_account = "";

	protected static function clean() {
		self::$active_account = self::$accounts['default'];
	}

}