<?php
if ($modx->event->name != "OnHandleRequest" || $modx->context->key == 'mgr') {
   return;
}
$tmp = explode('?', $_SERVER['REQUEST_URI']);
$link = trim($tmp[0], '/');
if (isset($tmp[1]) && $tmp[1]) {
    $params = '?' . $tmp[1];
} else {
    $params = '';
}
$uri = '/' . strtolower($link) . $params;
$http_host = $_SERVER['HTTP_HOST'];
$site_url = str_replace(array('www.', 'http://', 'https://', '/'), '', $modx->getOption('site_url'));

// for https set true
$https = false;

if ($https) {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}

if ($http_host != $site_url || ($https && !$_SERVER['HTTPS']) || $_SERVER['REQUEST_URI'] != $uri) {
    $modx->sendRedirect($protocol.$site_url.$uri, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
}
if ($_SERVER['REQUEST_URI'] == '/index.php') {
    $modx->sendRedirect($protocol.$site_url, array('responseCode' => 'HTTP/1.1 301 Moved Permanently'));
}
