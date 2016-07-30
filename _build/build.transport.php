<?php
require_once 'build.class.php';
$resolvers = array(
    'rename_htaccess',
    'remove_changelog',
    'cache_options',
    'resources',
    'settings',
    'providers',
    'addons',
    'fix_translit',
    'fix_fastuploadtv',
    'fix_directresize',
    'tvs',
    'manager_customisation'
);
$builder = new siteBuilder('site', '1.1.2', 'beta', $resolvers);
$builder->build();
