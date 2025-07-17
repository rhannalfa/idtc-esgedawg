<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

\Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
\Midtrans\Config::$clientKey = getenv('MIDTRANS_CLIENT_KEY');
\Midtrans\Config::$isProduction = getenv('MIDTRANS_IS_PRODUCTION') === 'true';
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;
