<?php
require_once 'build.class.php';
$resolvers = array(
    'providers',
    'addons',
    'rename_htaccess',
    'remove_changelog',
    'cache_options',
    'settings',
    'fix_translit',
    'fix_fastuploadtv',
    'fix_directresize',
    'template',
    'tvs',
    'resources',
    'manager_customisation'
);
$builder = new siteBuilder('site', '1.1.8', 'beta', $resolvers);
$builder->build();
