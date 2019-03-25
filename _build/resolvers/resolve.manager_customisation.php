<?php

/** @var $modx modX */
if (!$modx = $object->xpdo AND !$object->xpdo instanceof modX) {
    return true;
}

/** @var $options */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        if (!$profile = $modx->getObject('modFormCustomizationProfile', array('name' => 'Site'))) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Run <b>Form customisation</b>');
            $profile = $modx->newObject('modFormCustomizationProfile', array('name' => 'Site', 'active' => true));
            $profile->save();
        }
        $set = array('profile' => $profile->id);
        $set_list = array();
        if (!$set_list['create_set'] = $modx->getObject('modFormCustomizationSet', array_merge(array('action' => 'resource/create'), $set))) {
            if ($modx->getOption('cultureKey') == 'ru') {
                $description = 'Правила для новых страниц';
            } else {
                $description = 'Crating pages';
            }
            $set_list['create_set'] = $modx->newObject('modFormCustomizationSet', array_merge(array('action' => 'resource/create', 'description' => $description, 'active' => true), $set));
            $set_list['create_set']->save();
        }
        if (!$set_list['update_set'] = $modx->getObject('modFormCustomizationSet', array_merge(array('action' => 'resource/update'), $set))) {
            if ($modx->getOption('cultureKey') == 'ru') {
                $description = 'Правила для редактирования';
            } else {
                $description = 'Updating pages';
            }
            $set_list['update_set'] = $modx->newObject('modFormCustomizationSet', array_merge(array('action' => 'resource/update', 'description' => $description, 'active' => true), $set));
            $set_list['update_set']->save();
        }
        if ($tv = $modx->getObject('modTemplateVar', array('name' => 'img'))) {
            foreach ($set_list as $set) {
                $rule_data = array(
                        'set' => $set->id,
                        'action' => $set->action,
                        'name' => 'tv' . $tv->id,
                        'container' => 'modx-panel-resource',
                        'rule' => 'tvMove',
                        'value' => 'modx-resource-main-right',
                        'constraint_class' => 'modResource'
                    );
                if (!$rule = $modx->getObject('modActionDom', $rule_data)) {
                    $rule_data['active'] = true;
                    $rule = $modx->newObject('modActionDom', $rule_data);
                    $rule->save();
                }
            }
        }
        
        if ($tv = $modx->getObject('modTemplateVar', array('name' => 'show_on_page'))) {
            foreach ($set_list as $set) {
                $rule_data = array(
                        'set' => $set->id,
                        'action' => $set->action,
                        'name' => 'tv' . $tv->id,
                        'container' => 'modx-panel-resource',
                        'rule' => 'tvMove',
                        'value' => 'modx-resource-main-right',
                        'constraint_class' => 'modResource'
                    );
                if (!$rule = $modx->getObject('modActionDom', $rule_data)) {
                    $rule_data['active'] = true;
                    $rule = $modx->newObject('modActionDom', $rule_data);
                    $rule->save();
                }
            }
        }
        
        if ($tv = $modx->getObject('modTemplateVar', array('name' => 'keywords'))) {
            foreach ($set_list as $set) {
                $rule_data = array(
                        'set' => $set->id,
                        'action' => $set->action,
                        'name' => 'tv' . $tv->id,
                        'container' => 'modx-panel-resource',
                        'rule' => 'tvMove',
                        'value' => 'modx-resource-main-left',
                        'constraint_class' => 'modResource'
                    );
                if (!$rule = $modx->getObject('modActionDom', $rule_data)) {
                    $rule_data['active'] = true;
                    $rule = $modx->newObject('modActionDom', $rule_data);
                    $rule->save();
                }
            }
        }
        
        if ($tv = $modx->getObject('modTemplateVar', array('name' => 'subtitle'))) {
            foreach ($set_list as $set) {
                $rule_data = array(
                        'set' => $set->id,
                        'action' => $set->action,
                        'name' => 'tv' . $tv->id,
                        'container' => 'modx-panel-resource',
                        'rule' => 'tvMove',
                        'value' => 'modx-resource-main-left',
                        'constraint_class' => 'modResource'
                    );
                if (!$rule = $modx->getObject('modActionDom', $rule_data)) {
                    $rule_data['active'] = true;
                    $rule = $modx->newObject('modActionDom', $rule_data);
                    $rule->save();
                }
            }
        }
        
        /* Перенесено в ClientConfig
        if ($contacts = $modx->getObject('modResource', array('alias' => 'contacts', 'parent' => 0))) {
            $res_id = $contacts->get('id');
            $set_list = array();
            $set = array('profile' => $profile->id);
            if (!$set_list['update_set'] = $modx->getObject('modFormCustomizationSet', array_merge(array('action' => 'resource/update', 'constraint' => $res_id, 'constraint_field' => 'id', 'constraint_class' => 'modResource'), $set))) {
                $set_list['update_set'] = $modx->newObject('modFormCustomizationSet', array_merge(array('action' => 'resource/update', 'constraint' => $res_id, 'constraint_field' => 'id', 'constraint_class' => 'modResource', 'description' => 'Правила для страницы контактов', 'active' => true), $set));
                $set_list['update_set']->save();
            }
            if ($tv = $modx->getObject('modTemplateVar', array('name' => 'phone'))) {
                foreach ($set_list as $set) {
                    $rule_data = array(
                            'set' => $set->id,
                            'action' => $set->action,
                            'name' => 'tv' . $tv->id,
                            'container' => 'modx-panel-resource',
                            'rule' => 'tvMove',
                            'value' => 'modx-resource-main-right',
                            'constraint_class' => 'modResource'
                        );
                    if (!$rule = $modx->getObject('modActionDom', $rule_data)) {
                        $rule_data['active'] = true;
                        $rule = $modx->newObject('modActionDom', $rule_data);
                        $rule->save();
                    }
                }
            }
            
            if ($tv = $modx->getObject('modTemplateVar', array('name' => 'address'))) {
                foreach ($set_list as $set) {
                    $rule_data = array(
                            'set' => $set->id,
                            'action' => $set->action,
                            'name' => 'tv' . $tv->id,
                            'container' => 'modx-panel-resource',
                            'rule' => 'tvMove',
                            'value' => 'modx-resource-main-left',
                            'constraint_class' => 'modResource'
                        );
                    if (!$rule = $modx->getObject('modActionDom', $rule_data)) {
                        $rule_data['active'] = true;
                        $rule = $modx->newObject('modActionDom', $rule_data);
                        $rule->save();
                    }
                }
            }
            
            if ($tv = $modx->getObject('modTemplateVar', array('name' => 'email'))) {
                foreach ($set_list as $set) {
                    $rule_data = array(
                            'set' => $set->id,
                            'action' => $set->action,
                            'name' => 'tv' . $tv->id,
                            'container' => 'modx-panel-resource',
                            'rule' => 'tvMove',
                            'value' => 'modx-resource-main-left',
                            'constraint_class' => 'modResource'
                        );
                    if (!$rule = $modx->getObject('modActionDom', $rule_data)) {
                        $rule_data['active'] = true;
                        $rule = $modx->newObject('modActionDom', $rule_data);
                        $rule->save();
                    }
                }
            }
        }
        */
        
        if (in_array('MIGX', $options['install_addons'])) {
            $set_list = array();
            $set = array('profile' => $profile->id);
            if (!$set_list['update_set'] = $modx->getObject('modFormCustomizationSet', array_merge(array('action' => 'resource/update', 'constraint' => 0, 'constraint_field' => 'parent', 'constraint_class' => 'modResource'), $set))) {
                if ($modx->getOption('cultureKey') == 'ru') {
                    $description = 'Правила для страниц в корне сайта';
                } else {
                    $description = 'Root pages';
                }
                $set_list['update_set'] = $modx->newObject('modFormCustomizationSet', array_merge(array('action' => 'resource/update', 'constraint' => 0, 'constraint_field' => 'parent', 'constraint_class' => 'modResource', 'description' => $description, 'active' => true), $set));
                $set_list['update_set']->save();
            }
            if ($tv = $modx->getObject('modTemplateVar', array('name' => 'elements'))) {
                foreach ($set_list as $set) {
                    $rule_data = array(
                            'set' => $set->id,
                            'action' => $set->action,
                            'name' => 'tv' . $tv->id,
                            'container' => 'modx-panel-resource',
                            'rule' => 'tvMove',
                            'value' => 'modx-resource-main-left',
                            'constraint_class' => 'modResource'
                        );
                    if (!$rule = $modx->getObject('modActionDom', $rule_data)) {
                        $rule_data['active'] = true;
                        $rule = $modx->newObject('modActionDom', $rule_data);
                        $rule->save();
                    }
                }
            }
        }
        
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return true;