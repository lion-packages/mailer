<?php

namespace LionMailer\DataMailer;

use PHPMailer\PHPMailer\PHPMailer;

class Data {

	public function __construct() {

	}

	protected static function addData(PHPMailer $phpmailer, object $attach): PHPMailer {
		$address = $attach->addAddress;
		if (isset($address[1])) {
			$phpmailer->addAddress(trim($address[0]), trim($address[1]));
		} else {
			$phpmailer->addAddress(trim($address[0]));
		}

		$reply = $attach->addReplyTo;
		$phpmailer->addReplyTo(trim($reply[0]), trim($reply[1]));

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

	protected static function addGroupData(PHPMailer $phpmailer, object $attachs, object $newGroupInfo): PHPMailer {
		foreach ($attachs->addAddress as $key => $address) {
			if (isset($address[1])) {
				$phpmailer->addAddress(trim($address[0]), trim($address[1]));
			} else {
				$phpmailer->addAddress(trim($address[0]));
			}
		}

		$reply = $newGroupInfo->addReplyTo;
		$phpmailer->addReplyTo(trim($reply[0]), trim($reply[1]));

		if ($newGroupInfo->addCC != null) {
			$phpmailer->addCC(trim($newGroupInfo->addCC));
		}

		if ($newGroupInfo->addBCC != null) {
			$phpmailer->addBCC(trim($newGroupInfo->addBCC));
		}

		if ($newGroupInfo->addAttachment != null) {
			foreach ($newGroupInfo->addAttachment as $key => $file) {
				isset($file[1]) ? $phpmailer->addAttachment($file[0], $file[1]) : $phpmailer->addAttachment($file[0]);
			}
		}

		return $phpmailer;
	}

}