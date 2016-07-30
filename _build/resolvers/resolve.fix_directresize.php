<?php

if ($object->xpdo) {
    /** @var modX $modx */
    $modx =& $object->xpdo;
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $plugin = $modx->getObject('modPlugin', array('name' => 'DirectResize2'));
            if ($plugin) {
                $content = $plugin->get('plugincode');
                if (strpos($content, 'if (strpos("<!DOCTYPE html>")) $html5=true;') !== false) {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Run <b>Fix DirectResize2</b>');
                    $content = str_replace('if (strpos("<!DOCTYPE html>")) $html5=true;', '$html5=false;', $content);
                    $plugin->set('plugincode', $content);
                    $plugin->save();
                }
            }
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}
return true;