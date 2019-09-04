<?php

namespace snuzi\dblp;

use GuzzleHttp\Client;

class Request {

    public function getResult($baseUrl, $queryParams = [], $headers = [], $asArray = false)
    {
        $client = new Client();
        $response = $client->request('GET', $baseUrl, [
            'query' => $queryParams,
            'headers' => $headers
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception('Could not get any result ');
        }

        return json_decode($response->getBody()->getContents(), $asArray);
    }
}