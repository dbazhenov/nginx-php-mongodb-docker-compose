<?php

function fn_github_api_request(&$app, $url, $method, $params = []) 
{

    fn_github_api_request_limits_check($app);

    try {
        $response = $app['http']->request($method, $url , [
            'query' => $params
        ]);              

        $result = $response->getBody();

        $result = json_decode($result, true);

    } catch (GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        echo $responseBodyAsString;
    }  

    if (empty($result)) {
        $result = false;
    } 

    fn_github_api_request_limits_set($app, $response);

    return $result;

}

function fn_github_api_request_limits_set(&$app, $response)
{
    $headers = $response->getHeaders();

    $app['github_http']['limits']['remaining'] = (int) $headers['X-RateLimit-Remaining'][0];
    $app['github_http']['limits']['reset'] = (int) $headers['X-RateLimit-Reset'][0];

}

function fn_github_api_request_limits_check($app)
{

    if (isset($app['github_http']['limits'])) {

        $remaining = $app['github_http']['limits']['remaining'];

        if ($remaining == 0) {
            $reset = $app['github_http']['limits']['reset'] - time();

            fn_print_progress($app, 'Github API X-RateLimits will be reset in ' . $reset . ' sec.', true);
            sleep($reset+1);
        }
    }

}

function fn_github_save_repositories(&$app, $repositories)
{

    if (!empty($repositories['items'])) {
        foreach($repositories['items'] as $key => $repository) {

            $updateResult = $app['db']->repositories->updateOne(
                [
                    'id' => $repository['id'] // query 
                ],
                ['$set' => $repository],
                ['upsert' => true]
            );

            fn_add_num($app['report'], 'repositories');

        }
    } else {
        $updateResult = false;
    }

    return $updateResult;
}