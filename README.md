# Library created for easy email sending based on [PHPMailer](https://github.com/PHPMailer/PHPMailer).

## Install
```
composer require lion-framework/lion-mailer
```

## Usage
```php
require_once("vendor/autoload.php");

use LionMailer\Mailer;
use LionMailer\Attach;

Mailer::init([
	'class' => [
		'PHPMailer' => PHPMailer\PHPMailer\PHPMailer::class,
		'SMTP' => PHPMailer\PHPMailer\SMTP::class
	],
	'info' => [
		'debug' => 0, optional
		'host' => 'smtp.example',
		'port' => 111,
		'email' => "example@example.com",
		'user_name' => "example - user",
		'password' => "--example--"
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
```

### Instructions
The mailer class must be initialized using the init function and its respective parameters, debug 0 indicates that no type of information should appear on the screen when sending emails, deleting this property will cause information about the sending process to appear by default. <br>
```php
Mailer::init([
	'class' => [
		'PHPMailer' => PHPMailer\PHPMailer\PHPMailer::class,
		'SMTP' => PHPMailer\PHPMailer\SMTP::class
	],
	'info' => [
		'debug' => 0,
		'host' => 'smtp.example',
		'port' => 111,
		'email' => "example@example.com",
		'user_name' => "example - user",
		'password' => "--example--"
	]
]);
```

The class property of the array indicates that the displayed classes must be initialized for the email class to work.
```php
'class' => [
	'PHPMailer' => PHPMailer\PHPMailer\PHPMailer::class,
	'SMTP' => PHPMailer\PHPMailer\SMTP::class
]
```

The info property relates all kinds of user credentials for sending correct email.
```php
'info' => [
	'debug' => 0,
	'host' => 'smtp.example',
	'port' => 111,
	'email' => "example@example.com",
	'user_name' => "example - user",
	'password' => "--example--"
]
```