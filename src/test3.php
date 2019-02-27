<?php
if (!extension_loaded('parallel')) {
    die('Parallel PHP required' . PHP_EOL);
}

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Pool;
use GuzzleHttp\Client;

$dir = __DIR__ . '/../images/test3/';
$urlBase = 'https://api.adorable.io/avatars/285/';
$runs = 100;

exec('rm -f ' . $dir . '/*.png');

$client = new Client([
    'base_uri' => $urlBase
]);

$requests = function($runs) use ($client, $dir) {
    for ($x = 0; $x < $runs; $x++) {
        $url = 'test-3' . $x;
        $file = $dir . $x . '.png';
        yield function () use ($client, $url, $file) {
            return $client->getAsync($url, ['sink' => $file]);
        };
    }
};

$pool = new Pool($client, $requests($runs));
$promise = $pool->promise();
$promise->wait();
