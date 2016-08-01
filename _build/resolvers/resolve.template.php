<?php

/** @var $modx modX */
if (!$modx = $object->xpdo AND !$object->xpdo instanceof modX) {
    return true;
}

/** @var $options */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        if (isset($options['site_template_name']) && !empty($options['site_template_name'])) {
            
            $template = $modx->getObject('modTemplate', array('templatename' => $options['site_template_name']));
            
            if (!$tmp = $modx->getObject('modSystemSetting', array('key' => 'default_template'))) {
                $tmp = $modx->newObject('modSystemSetting');
            }
            $tmp->fromArray(array(
                'namespace' => 'core',
                'area'      => 'site',
                'xtype'     => 'textfield',
                'value'     => $template->get('id'),
                'key'       => 'default_template',
            ), '', true, true);
            $tmp->save();
            
            $site_start = $modx->getObject('modResource', $modx->getOption('site_start'));
            if ($site_start) {
                $site_start->set('template', $template->get('id'));
                $site_start->save();
            }
        }
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return true;