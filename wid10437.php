<?php

function file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
    return mb_convert_encoding($content, 'UTF-8',
        mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}

$remoteAddr = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
$proxyUrl = 'http://85.119.151.35/'.$remoteAddr;

// Get file
$contents = @file_get_contents_utf8($proxyUrl);
if ($contents === false) {
    header("HTTP/1.1 503 Proxy error");
    die("Proxy failed to get contents at $proxyUrl");
}


// Headers
$headers = $http_response_header;
// $allowedHeaders = "!^(http/1.1|server:|content-type:|last-modified|access-control-allow-origin|Content-Length:|Accept-Ranges:|Date:|Via:|Connection:|X-|age|cache-control|vary)!i";
$allowedHeaders = "!^(content-type:|access-control-allow-origin)!i";
if(!empty($headers)) {
    foreach ($headers as $header) {
    if (preg_match($allowedHeaders, $header)) {
        header($header);
    }
}
}


// Preg replace
$self = $_SERVER['SCRIPT_NAME'].'?';
if (preg_match('/index\.html/', $proxyUrl)) {
    $uri = $self.preg_replace('/index\.html/', 'bundle.js', $remoteAddr);
    $contents = preg_replace('/bundle\.js\?.*"/', $uri.'"', $contents);
} elseif (preg_match('/bundle\.js/', $proxyUrl)) {

} else {
    $contents = preg_replace('/(https?:)?\/\/uberlaw\.ru\//', $self, $contents);
}
echo $contents;
