<?php

// Enabling Composer Packages
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/func/common.php';

// Get environment variables
$local_conf = getenv();
define('DB_USERNAME', $local_conf['DB_USERNAME']);
define('DB_PASSWORD', $local_conf['DB_PASSWORD']);
define('DB_HOST', $local_conf['DB_HOST']);

// Connect to MongoDB
$db_client = new \MongoDB\Client('mongodb://'. DB_USERNAME .':' . DB_PASSWORD . '@'. DB_HOST . ':27017/');

$app['db'] = $db_client->selectDatabase('tutorial');

if (isset($local_conf['GITHUB_TOKEN'])) {

    define('GITHUB_TOKEN', $local_conf['GITHUB_TOKEN']);

    $app['http'] = new \GuzzleHttp\Client(
        ['headers' => 
            [
               'Authorization' => 'Bearer ' . GITHUB_TOKEN
            ]
        ]
    );

} else {
    $app['http'] = new \GuzzleHttp\Client();  
}

$app['report'] = [
    'timer' => [
        'start' => time()
    ]
];

$app['profiler'] = [
    'start' => hrtime(true)
];
