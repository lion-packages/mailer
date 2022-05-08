<?php

namespace LionMailer\DataMailer;

use PHPMailer\PHPMailer\PHPMailer;

class Data {

	private function __construct() {

	}

	protected static function addData(PHPMailer $phpmailer, object $attach): PHPMailer {
		$address = $attach->addAddress;
		if (isset($address[1])) {
			$phpmailer->addAddress(trim($address[0]), trim($address[1]));
		} else {
			$phpmailer->addAddress(trim($address[0]));
		}

		if ($attach->addReplyTo != null) {
			$reply = $attach->addReplyTo;
			$phpmailer->addReplyTo(trim($reply[0]), trim($reply[1]));
		}

		if ($attach->addCC != null) {
			$phpmailer->addCC(trim($attach->addCC));
		}

		if ($attach->addBCC != null) {
			$phpmailer->addBCC(trim($attach->addBCC));
		}

		if ($attach->addAttachment != null) {
			foreach ($attach->addAttachment as $key => $file) {
				isset($file[1]) ? $phpmailer->addAttachment($file[0], $file[1]) : $phpmailer->addAttachment($file[0]);
			}
		}

		return $phpmailer;
	}

}