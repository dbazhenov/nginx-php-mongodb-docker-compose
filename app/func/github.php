<?php

function fn_github_get_repositories($app) 
{

    $http = $app['http'];
    
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

    return $result;
}