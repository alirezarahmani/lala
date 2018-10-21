<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\DistanceCalculatorInterface;
use App\Domain\Model\Route;
use Assert\Assertion;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class HereMapDistance implements DistanceCalculatorInterface
{
    private $client;
    private $uri = 'https://route.api.here.com/routing/7.2/calculateroute.json';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function calculate(Route $route): int
    {
        $response = $this->client->request('GET', $this->uri, [
            'query' => [
                    'waypoint0'=> (string)$route->origin(),
                    'waypoint1'=> (string)$route->destination(),
                    'mode' => 'fastest;car;traffic:enabled',
                    'app_id' => 'devportal-demo-20180625',
                    'app_code' => '9v2BkviRwi9Ot26kp2IysQ',
                    'departure' => 'now'
                ]
        ]);
        Assertion::eq($response->getStatusCode(), Response::HTTP_OK, 'distance service error');
        $result = json_decode($response->getBody()->getContents(), true);
        Assertion::true(isset($result['response']['route'][0]['summary']['distance']), 'wrong response from HereMap');
        return $result['response']['route'][0]['summary']['distance'];
    }
}