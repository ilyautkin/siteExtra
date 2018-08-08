<?php

/** @var $modx modX */
if (!$modx = $object->xpdo AND !$object->xpdo instanceof modX) {
    return true;
}

/** @var $options */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

        if ($contentType = $modx->getObject('modContentType', array('name' => 'HTML'))) {
            $contentType->set('file_extensions', '');
            $contentType->save();
        }

        break;

    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return true;
