<?php

if ($object->xpdo) {
	/** @var modX $modx */
	$modx =& $object->xpdo;
    
    $file = $modx->getOption('core_path') . 'xpdo/cache/xpdocachemanager.class.php';
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:
			if (file_exists($file)) {
			    $modx->log(modX::LOG_LEVEL_INFO, 'Run <b>Fix file permissions</b>');
			    $content = file_get_contents($file);
                $fp = fopen($file, "w");
                $content = str_replace('(0666 - $this->_umask)', '(0777 - $this->_umask)', $content);
                fwrite($fp, $content);
                fclose($fp);
			}
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;