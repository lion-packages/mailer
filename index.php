<?php

require_once('./vendor/autoload.php');

header('Content-Type: application/json');

use Lion\Mailer\Mailer;
use Lion\Mailer\Priority;

Mailer::initialize([
    'lion-mailer' => [
        'name' => 'lion-mailer',
        'type' => 'phpmailer',
        'host' => 'mailhog',
        'username' => 'root@dev.com',
        'password' => 'lion',
        'port' => 1025,
        'encryption' => false,
        'debug' => false
    ],
], 'lion-mailer');

echo json_encode([
    'isValid' => Mailer::account('lion-mailer')
        ->subject('Test Priority')
        ->from('sleon@dev.com', 'Sleon')
        ->addAddress('jjerez@dev.com', 'Jjerez')
        ->body('Send Mailer')
        ->priority(Priority::HIGH)
        ->send()
]);
