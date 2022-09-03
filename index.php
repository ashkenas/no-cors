<?php
if (!isset($_GET['u'])) {
    header("HTTP/1.1 404 Not Found");
    die('No URL provided.');
}

$url = $_GET['u'];
$host = parse_url($url, PHP_URL_HOST);

$opts = [
    "http" => [
        "method" => "GET",
        "header" => "Origin: {$host}\r\n" .
            "Referer: {$url}\r\n"
    ]
];

$context = stream_context_create($opts);

if (isset($_SERVER['HTTP_ORIGIN']))
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {        
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    die;
}

header("HTTP/1.1 200 OK");

$fp = fopen($url, 'r', false, $context);
fpassthru($fp);
fclose($fp);
?>
