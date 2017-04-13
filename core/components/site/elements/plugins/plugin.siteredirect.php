<?php
if ($modx->event->name != "OnHandleRequest" || $modx->context->key == 'mgr') {
   return;
}
$uri = $_SERVER['REQUEST_URI'];
$http_host = $_SERVER['HTTP_HOST'];
$site_url = str_replace(array('www.', 'http://', 'https://', '/'), '', $modx->getOption('site_url'));

// for https set true
$https = false;

// robots.txt allways without redirect
if ($uri == '/robots.txt') return;

if ($https) {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}

if ($http_host != $site_url || ($https && !$_SERVER['HTTPS'])) {
    $modx->sendRedirect($protocol.$site_url.$uri, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
}
if ($_SERVER['REQUEST_URI'] == '/index.php') {
    $modx->sendRedirect($protocol.$site_url, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
}
