<?php

if ($object->xpdo) {
	/** @var modX $modx */
	$modx =& $object->xpdo;
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		    $modx->log(modX::LOG_LEVEL_INFO, 'Run <b>Set start year for copyright</b>');
            
            if ($tmp = $modx->getObject('modChunk', array('name' => 'footer'))) {
                $tmp->set('content', str_replace(
                    "{'!year' | snippet : ['start' => '']}",
                    "{'!year' | snippet : ['start' => '".date('Y')."']}", $tmp->get('content')));
                $tmp->save();
            }
			break;
		case xPDOTransport::ACTION_UPGRADE:
		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;