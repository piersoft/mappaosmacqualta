<?php

$url = 'http://paolomainardi.com:3050/api/devices?type=geo';
$file = "mappa.json";
$src = fopen($url, 'r');
$dest = fopen($file, 'w');
echo stream_copy_to_stream($src, $dest) . "";
sleep(1);
header("Location:http://www.apposta.biz/prove/mappacqualta.html");

?>

