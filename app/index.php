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

$http = new \GuzzleHttp\Client();

$url = 'https://api.github.com/search/repositories';

$params = [
    'q' => 'topic:mongodb',
    'sort' => 'help-wanted-issues'
];

try {
    $response = $http->request('GET', $url , [
        'query' => $params
    ]);              

    $result = $response->getBody();

    $result = json_decode($result, true);

} catch (GuzzleHttp\Exception\ClientException $e) {
    $response = $e->getResponse();
    $responseBodyAsString = $response->getBody()->getContents();
    echo $responseBodyAsString;
}  

// Create an index
$db->repositories->createIndex(['id' => 1]);

if (!empty($result['items'])) {
    foreach($result['items'] as $key => $repository) {

        $updateResult = $db->repositories->updateOne(
            [
                'id' => $repository['id'] // query 
            ],
            ['$set' => $repository],
            ['upsert' => true]
        );

    }
}

dd($result);
