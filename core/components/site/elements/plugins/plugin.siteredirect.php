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

if ($http_host != $site_url || ($https && !$_SERVER['HTTPS'])) {
    if ($https) {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }
    $modx->sendRedirect($protocol.$site_url.$uri, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
}