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
            $set_list['create_set'] = $modx->newObject('modFormCustomizationSet', array_merge(array('action' => 'resource/create', 'active' => true), $set));
            $set_list['create_set']->save();
        }
        if (!$set_list['update_set'] = $modx->getObject('modFormCustomizationSet', array_merge(array('action' => 'resource/update'), $set))) {
            $set_list['update_set'] = $modx->newObject('modFormCustomizationSet', array_merge(array('action' => 'resource/update', 'active' => true), $set));
            $set_list['update_set']->save();
        }
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => 'img'))) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Faled. TemplateVar <b>img</b> not found');
            return true;
        }
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
        
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => 'show_child'))) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Faled. TemplateVar <b>show_child</b> not found');
            return true;
        }
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
        
        
        if ($contacts = $modx->getObject('modResource', array('alias' => 'contacts', 'parent' => 0))) {
            $res_id = $contacts->get('id');
            $set_list = array();
            $set = array('profile' => $profile->id);
            if (!$set_list['create_set'] = $modx->getObject('modFormCustomizationSet', array_merge(array('action' => 'resource/create', 'constraint' => $res_id, 'constraint_field' => 'id', 'constraint_class' => 'modResource'), $set))) {
                $set_list['create_set'] = $modx->newObject('modFormCustomizationSet', array_merge(array('action' => 'resource/create', 'constraint' => $res_id, 'constraint_field' => 'id', 'constraint_class' => 'modResource', 'active' => true), $set));
                $set_list['create_set']->save();
            }
            if (!$set_list['update_set'] = $modx->getObject('modFormCustomizationSet', array_merge(array('action' => 'resource/update', 'constraint' => $res_id, 'constraint_field' => 'id', 'constraint_class' => 'modResource'), $set))) {
                $set_list['update_set'] = $modx->newObject('modFormCustomizationSet', array_merge(array('action' => 'resource/update', 'constraint' => $res_id, 'constraint_field' => 'id', 'constraint_class' => 'modResource', 'active' => true), $set));
                $set_list['update_set']->save();
            }
            if (!$tv = $modx->getObject('modTemplateVar', array('name' => 'phone'))) {
                $modx->log(modX::LOG_LEVEL_INFO, 'Faled. TemplateVar <b>phone</b> not found');
                return true;
            }
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
            
            if (!$tv = $modx->getObject('modTemplateVar', array('name' => 'address'))) {
                $modx->log(modX::LOG_LEVEL_INFO, 'Faled. TemplateVar <b>address</b> not found');
                return true;
            }
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
            
            if (!$tv = $modx->getObject('modTemplateVar', array('name' => 'email'))) {
                $modx->log(modX::LOG_LEVEL_INFO, 'Faled. TemplateVar <b>email</b> not found');
                return true;
            }
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
        
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return true;