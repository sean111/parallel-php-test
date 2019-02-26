<?php

$dir = __DIR__ . '/images/test2/';
$urlBase = 'https://api.adorable.io/avatars/285/test2-';
$runs = 100;

exec('rm -f ' . $dir . '/*.png');

for ($x = 0; $x < $runs; $x++) {
    $worker[$x] = new \parallel\Runtime();
    $worker[$x]->run(function($dir, $urlBase, $x) {
        $file = $dir . $x . '.png';
        $url = $urlBase . $x;
        $fp = fopen($file, 'w');
        $cp = curl_init($urlBase . $x);
        curl_setopt($cp, CURLOPT_TIMEOUT, 50);
        curl_setopt($cp, CURLOPT_FILE, $fp); 
        curl_setopt($cp, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($cp); 
        curl_close($cp);
        fclose($fp);
    }, [$dir, $urlBase, $x]);
}