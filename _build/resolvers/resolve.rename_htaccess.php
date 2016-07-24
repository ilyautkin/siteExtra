<?php

if ($object->xpdo) {
    /** @var modX $modx */
    $modx =& $object->xpdo;
    
    $inroot = $modx->getOption('base_path') . 'ht.access';
    $incore = $modx->getOption('core_path') . 'ht.access';
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            if (file_exists($inroot) || file_exists($incore)) {
                $new_inroot = $modx->getOption('base_path') . '.htaccess';
                $new_incore = $modx->getOption('core_path') . '.htaccess';
                $log = false;
                if (!file_exists($new_inroot)) {
                    rename($inroot, $new_inroot);
                    $log = true;
                }
                if (!file_exists($new_incore)) {
                    rename($incore, $new_incore);
                    $log = true;
                }
                if ($log) {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Renaming <b>htaccess</b>');
                }
            }
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}
return true;