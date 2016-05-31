<?php
require_once 'build.class.php';
$resolvers = array(
    'resources',
    'settings',
    'providers',
    'addons',
    'fix_translit'
);
$builder = new siteBuilder('site', '1.0.7', 'beta', $resolvers);
$builder->build();
