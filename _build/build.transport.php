<?php
require_once 'build.class.php';
$resolvers = array(
    //'fix_file_permissions',
    'rename_htaccess',
    'resources',
    'settings',
    'providers',
    'addons',
    'fix_translit',
    'fix_fastuploadtv',
    'remove_changelog',
    'tvs'
);
$builder = new siteBuilder('site', '1.1.0', 'beta', $resolvers);
$builder->build();
