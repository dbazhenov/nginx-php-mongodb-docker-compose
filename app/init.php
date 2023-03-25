<?php

// Enabling Composer Packages
require __DIR__ . '/vendor/autoload.php';

// Get environment variables
$local_conf = getenv();
define('DB_USERNAME', $local_conf['DB_USERNAME']);
define('DB_PASSWORD', $local_conf['DB_PASSWORD']);
define('DB_HOST', $local_conf['DB_HOST']);

// Connect to MongoDB
$db_client = new \MongoDB\Client('mongodb://'. DB_USERNAME .':' . DB_PASSWORD . '@'. DB_HOST . ':27017/');

$app['db'] = $db_client->selectDatabase('tutorial');

$app['http'] = new \GuzzleHttp\Client();