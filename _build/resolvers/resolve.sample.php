<?php

if ($object->xpdo) {
	/** @var modX $modx */
	$modx =& $object->xpdo;
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		    $modx->log(modX::LOG_LEVEL_INFO, 'Run <b>Sample</b> resolver install');
			break;
		case xPDOTransport::ACTION_UPGRADE:
			$modx->log(modX::LOG_LEVEL_INFO, 'Run <b>Sample</b> resolver upgrade');
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;