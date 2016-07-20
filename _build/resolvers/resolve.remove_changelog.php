<?php

if ($object->xpdo) {
	/** @var modX $modx */
	$modx =& $object->xpdo;
    
    $file = $modx->getOption('core_path') . 'docs/changelog.txt';
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:
			if (file_exists($file)) {
			    $modx->log(modX::LOG_LEVEL_INFO, 'Removing <b>changelog.txt</b>');
			    unlink($file);
			}
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;