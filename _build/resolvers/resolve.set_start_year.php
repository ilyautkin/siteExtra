<?php

if ($object->xpdo) {
	/** @var modX $modx */
	$modx =& $object->xpdo;
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
            if ($tmp = $modx->getObject('modChunk', array('name' => 'footer'))) {
                if (strpos($tmp->get('content'), '{if $year == 2016}2016{else}2016—{$year}{/if}') !== false) {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Run <b>Set start year for copyright</b>');
                    $tmp->set('content', str_replace(
                        '{if $year == 2016}2016{else}2016—{$year}{/if}',
                        '{if $year == '.date('Y').'}'.date('Y').'{else}'.date('Y').'—{$year}{/if}',
                        $tmp->get('content')));
                    $tmp->save();
                }
            }
			break;
		case xPDOTransport::ACTION_UPGRADE:
		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;