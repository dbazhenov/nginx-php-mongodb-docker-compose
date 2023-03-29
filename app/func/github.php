<?php


function fn_github_api_request($app, $url, $method, $params = []) 
{

    $http = $app['http'];

    try {
        $response = $http->request($method, $url , [
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
    return $result;

}

function fn_github_save_repositories($app, $repositories)
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

        }
    } else {
        $updateResult = false;
    }

    return $updateResult;
}