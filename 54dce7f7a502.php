<?php

header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$proxyDomain = 'http://wdgt.justiva.ru/';
$remoteAddr = null;

function sendError($error) {
    global $proxyDomain;
    $error['server'] = $_SERVER['SERVER_NAME'];
    $error['request_uri'] = $_SERVER['REQUEST_URI'];

    $error = json_encode($error);
    $loggerUrl  = $proxyDomain.'logErrors.php';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $loggerUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'data='.$error);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl);
    $curl_error = curl_error($curl);
    curl_close($curl);

};

function my_error_handler ($errno, $errstr, $errfile, $errline)
{
    $error = array(
        'type' => 'php',
        'data' => array(
            'no'=>$errno,
            'str'=>$errstr,
            'file'=>$errfile,
            'line'=>$errline
        )
    );

    sendError($error);
}

set_error_handler("my_error_handler");

function file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
    return mb_convert_encoding($content, 'UTF-8',
        mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}

if(!empty($_SERVER['QUERY_STRING']))
{
    $remoteAddr = trim($_SERVER['QUERY_STRING']);
}

if (is_null($remoteAddr)) {
    $remoteAddr = 'js/widget-a-b.js';
}

if ($remoteAddr === "version") {
    echo "v.1.1.1<br/>\n";
    echo phpversion();
    die();
}

$proxyUrl = $proxyDomain.$remoteAddr;

// Get file
$contents = @file_get_contents_utf8($proxyUrl);
if ($contents === false) {
    sendError(array('type' => 'internal', 'data' => 'file_get_contents === false'));
    header("HTTP/1.1 503 Proxy error");
    die("Proxy failed to get contents at $proxyUrl");
}

// Preg replace
$self = $_SERVER['SCRIPT_NAME'].'?';
if (preg_match('/index\.html/', $proxyUrl)) {
    $uri = $self.preg_replace('/index\.html/', 'bundle.js', $remoteAddr);
    $contents = preg_replace('/bundle\.js\?.*"/', $uri.'"', $contents);
    header('Content-Type: text/html');
} elseif (preg_match('/bundle\.js/', $proxyUrl)) {
    header('Content-Type: application/javascript');
} else {
    header('Content-Type: application/javascript');
    $contents = preg_replace('/(https?:)?\/\/uberlaw\.ru\//', $self, $contents);
}

echo $contents;