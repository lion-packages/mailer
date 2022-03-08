<?php

require_once("vendor/autoload.php");
spl_autoload_register(function($class_name) {
	include_once(str_replace("\\", "/", $class_name) . '.php');
});

use LionMailer\Mailer;
use LionMailer\Attach;

Mailer::init([
	'class' => [
		'PHPMailer' => PHPMailer\PHPMailer\PHPMailer::class,
		'SMTP' => PHPMailer\PHPMailer\SMTP::class
	],
	'info' => [
		'debug' => 0,
		'host' => 'SMTP.Office365.com',
		'port' => 587,
		'email' => "example-dev@outlook.com",
		'user_name' => "example - user",
		'password' => "attach-user"
	]
]);

$request = Mailer::send(
	new Attach(
		['example@gmail.com', 'User - Dev'], 
		['example2@gmail.com', 'User - Reply to'], 
		null, 
		null, 
		null, 
		'Sujeto de prueba', 
		'body de prueba', 
		'alt body de prueba'
	)
);

var_dump($request);