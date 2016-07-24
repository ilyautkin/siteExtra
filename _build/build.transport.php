<?php
require_once 'build.class.php';
$resolvers = array(
    //'fix_file_permissions',
    'rename_htaccess',
    'remove_changelog',
    'cache_options',
    'resources',
    'settings',
    'providers',
    'addons',
    'fix_translit',
    'fix_fastuploadtv',
    'tvs',
    'manager_customisation'
);
$builder = new siteBuilder('site', '1.1.1', 'beta', $resolvers);
$builder->build();
