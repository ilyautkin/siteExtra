<?php

if ($object->xpdo) {
    /** @var modX $modx */
    $modx =& $object->xpdo;
    
    $file = $modx->getOption('core_path') . 'config/' . MODX_CONFIG_KEY.'.inc.php';
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            if (file_exists($file)) {
                $content = file_get_contents($file);
                if (strpos($content, 'cache_handler') === false) {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Run <b>Cache options</b>');
                    $fp = fopen($file, "w");
                    $prefix = substr(md5(time()), 0, 8) . '_';
                    $_SESSION['setting_cache_prefix'] = $prefix;
                    $content = str_replace(
                              '$config_options = array ('. PHP_EOL .');',
                              
                              '$config_options = array ('. PHP_EOL .
                                '//"cache_prefix" => "' . $prefix . '",'. PHP_EOL .
                                '//"cache_handler" => "cache.xPDOMemCached"' . PHP_EOL .
                              ');',
                              
                              $content);
                    fwrite($fp, $content);
                    fclose($fp);
                }
            }
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}
return true;
