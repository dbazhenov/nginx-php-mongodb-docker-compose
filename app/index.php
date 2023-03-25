<?php

// Enabling Composer Packages
require __DIR__ . '/init.php';
require __DIR__ . '/func/github.php';

$repositories = fn_github_get_repositories($app);

$app['db']->repositories->createIndex(['id' => 1]);

if (!empty($repositories['items'])) {
    foreach($repositories['items'] as $key => $repository) {

        $updateResult = $app['db']->repositories->updateOne(
            [
                'id' => $repository['id'] // query 
            ],
            ['$set' => $repository],
            ['upsert' => true]
        );

    }
}

dd($repositories['items']);
