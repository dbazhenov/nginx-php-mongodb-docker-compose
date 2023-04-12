<?php

// Enabling Composer Packages
require __DIR__ . '/init.php';
require __DIR__ . '/func/github.php';

$url = 'https://api.github.com/search/repositories';

$params = [
    'q' => 'topic:mongodb',
    'sort' => 'help-wanted-issues'
];

for ($i = 1; $i <= 10; $i++) {

    $params['page'] = $i;

    $repositories = fn_github_api_request($app, $url, 'GET', $params);

    fn_github_save_repositories($app, $repositories);

    fn_print_progress($app, "Page " . $i . ", Rate limit remaining: " . $app['github_http']['limits']['remaining'], true);
}

fn_finish($app);