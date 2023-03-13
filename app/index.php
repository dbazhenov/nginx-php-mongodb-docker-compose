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

$db = $db_client->selectDatabase('tutorial');

// Create an index
$db->pages->createIndex(['page_id' => 1]);

// Test insert data
for ($page = 1; $page <= 1000; $page++) {

    $data = [
        'page_id' => $page, 
        'title' => "Page " . $page,
        'date' => date("m.d.y H:i:s"),
        'timestamp' => time(),
        'mongodb_time' => new MongoDB\BSON\UTCDateTime(time() * 1000)
    ];

    $updateResult = $db->pages->updateOne(
        [
            'page_id' => $page // query 
        ],
        ['$set' => $data],
        ['upsert' => true]
    );

    echo $page . " " ;
}
echo '<br/>Finish';
exit;