<?php
require_once 'build.class.php';
$resolvers = array('sample');
$builder = new siteBuilder('ilyaut', '1.0.7', 'beta', $resolvers);
$builder->build();
