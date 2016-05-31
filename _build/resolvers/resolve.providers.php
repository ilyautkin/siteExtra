<?php

if ($object->xpdo) {
	/* @var modX $modx */
	$modx =& $object->xpdo;

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:
		    $provider_name = 'modstore.pro';
			if (!$provider = $modx->getObject('transport.modTransportProvider', array('service_url:LIKE' => '%' . $provider_name . '%'))) {
				$provider = $modx->newObject('transport.modTransportProvider', array(
					'name' => $provider_name,
					'service_url' => 'http://' . $provider_name . '/extras/',
					'username' => !empty($options['email']) && preg_match('/.+@.+\..+/i', $options['email']) ? trim($options['email']) : '',
					'api_key' => !empty($options['key']) ? trim($options['key']) : '',
					'description' => 'Repository of ' . $provider_name,
					'created' => time(),
				));
				$provider->save();
			}
			break;
		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;
