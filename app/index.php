<?php

// Enabling Composer Packages
require __DIR__ . '/init.php';
require __DIR__ . '/func/github.php';

$url = 'https://api.github.com/search/repositories';

$params = [
    'q' => 'topic:mongodb',
    'sort' => 'help-wanted-issues'
];

for ($i = 1; $i <= 30; $i++) {

    $params['page'] = $i;

    $repositories = fn_github_api_request($app, $url, 'GET', $params);

    fn_github_save_repositories($app, $repositories);

    echo "Page: " . $i . " ";
}

dd($repositories);
