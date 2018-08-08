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
    'manager_customisation'
);
$addons = array(
    array('name' => '', 'packages' => array(
            'simpleUpdater' => '2.1.2-rc',
            'FormIt' => '4.1.0-pl',
            'CKEditor' => '1.4.0-pl',
            'TinyMCE Rich Text Editor' => '1.2.1-pl',
            'Collections' => '3.6.0-pl',
            'Console' => '2.2.1-beta',
            'MIGX' => '2.12.0-pl',
            'translit' => '1.0.0-beta',
            'VersionX' => '2.1.3-pl',
            'SmushIt' => '1.0.0-beta'
        )),
    array('name' => 'modstore.pro', 'packages' => array(
            'Ace' => '1.6.5-pl',
            'autoRedirector' => '0.1.0-beta',
            'pdoTools' => '2.11.2-pl',
            'AjaxForm' => '1.1.9-pl',
            'MinifyX' => '1.6.0-pl',
            'phpThumbOn' => '1.3.3-beta',
            'tagElementPlugin' => '1.2.4-pl1',
            'frontendManager' => '1.1.1-beta',
            'FastUploadTV' => '1.0.0-pl'
        )),
);
$builder = new siteBuilder('site', '1.4.7', 'rc', $resolvers, $addons);
$builder->build();
