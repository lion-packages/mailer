<?php

// phpcs:disable PSR1.Files.SideEffects

require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;

if (!file_exists(__DIR__ . '/../.env')) {
    throw new RuntimeException('You must add the .env');
}

Dotenv::createMutable(__DIR__ . '/../')->load();

/** @var string $host */
$host = $_ENV['MAIL_HOST'];

define('HOST', $host);
