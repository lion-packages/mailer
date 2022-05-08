<?php

namespace LionMailer\DataMailer;

class Attach {

	protected static ?object $attach = null;

	protected function __construct() {

	}

	public static function newAttach(?array $addAddress = [], ?array $addReplyTo = [], ?string $addCC = null, ?string $addBCC = null, ?array $addAttachment = []): object {
		if (self::$attach === null) {
			self::$attach = (object) [
				'addAddress' => $addAddress,
				'addReplyTo' => $addReplyTo,
				'addCC' => $addCC,
				'addBCC' => $addBCC,
				'addAttachment' => $addAttachment
			];
		} else {
			self::$attach->addAddress = $addAddress;
			self::$attach->addReplyTo = $addReplyTo;
			self::$attach->addCC = $addCC;
			self::$attach->addBCC = $addBCC;
			self::$attach->addAttachment = $addAttachment;
		}

		return self::$attach;
	}

}