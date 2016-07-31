<?php

if ($object->xpdo) {
    /** @var modX $modx */
    $modx =& $object->xpdo;
    
    $file = $modx->getOption('base_path') . 'assets/components/fastuploadtv/mgr/js/FastUploadTV.form.FastUploadTVField.js';
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            if (file_exists($file)) {
                $content = file_get_contents($file);
                if ($modx->getOption('connectors_url') != '/connectors/' && strpos($content, '/connectors/') !== false) {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Run <b>Fix FastUploadTV</b>');
                    $fp = fopen($file, "w");
                    $content = str_replace('/connectors/', $modx->getOption('connectors_url'), $content);
                    fwrite($fp, $content);
                    fclose($fp);
                }
                if (strpos($content, 'phpthumb.php?w=94&zc=0') !== false) {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Run <b>Fix FastUploadTV</b>');
                    $fp = fopen($file, "w");
                    $content = str_replace('phpthumb.php?w=94&zc=0', 'phpthumb.php?w=130&h=73&zc=1', $content);
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