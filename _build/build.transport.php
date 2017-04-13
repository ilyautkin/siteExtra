<?php
require_once 'build.class.php';
$resolvers = array(
    'providers',
    'addons',
    'rename_htaccess',
    'remove_changelog',
    'cache_options',
    'template',
    'tvs',
    'resources',
    'settings',
    'set_start_year',
    'fix_translit',
    /* 'fix_fastuploadtv', */
    'manager_customisation'
);
$builder = new siteBuilder('site', '1.2.5', 'beta', $resolvers);
$builder->build();
