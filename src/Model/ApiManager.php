<?php

namespace App\Model;

use Symfony\Component\HttpClient\HttpClient;

class ApiManager extends AbstractManager
{
    public function addApi(): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', "https://api.nasa.gov/mars-photos/api/v1/rovers/Opportunity/photos?sol=20&camera=PANCAM&page=2&api_key=DEMO_KEY");
        $statusCode = $response->getStatusCode();
        $result = [];

        if ($statusCode === 200) {
            $content = $response->getContent();
            $content = $response->toArray();

            for ($i = 0; $i < \count($content["photos"]); $i++) {
                $result[] = $content["photos"][$i]["img_src"];
            }
        }
        return $result;
    }
}
