<?php

if ($object->xpdo) {
	/** @var modX $modx */
	$modx =& $object->xpdo;
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:
            $path = $modx->getOption('clientconfig.core_path', null, $modx->getOption('core_path') . 'components/clientconfig/');
            $path .= 'model/clientconfig/';
            $clientConfig = $modx->getService('clientconfig','ClientConfig', $path);
            
            if ($clientConfig instanceof ClientConfig) {
                if (!$groups = $modx->getCollection('cgGroup')) {
                    $group = $modx->newObject('cgGroup');
                    $group->set('label', 'Контактная информация');
                    $group->set('description', '');
                    $group->save();
                    
                    $settings = [
                        ['key' => 'address', 'label' => 'Адрес', 'value' => 'г. Москва, ул. Печатников, д. 17, оф. 350'],
                        ['key' => 'phone', 'label' => 'Телефон', 'value' => '+7 (499) 150-22-22'],
                        ['key' => 'email', 'label' => 'E-mail', 'value' => 'info@company.ru'],
                        ['key' => 'emailto', 'label' => 'E-mail для заявок', 'value' => $modx->getOption('emailsender')],
                    ];
                    
                    foreach ($settings as $idx => $data) {
                        $setting = $modx->newObject('cgSetting');
                        $setting->set('key', $data['key']);
                        $setting->set('label', $data['label']);
                        $setting->set('value', $data['value']);
                        $setting->set('xtype', 'textfield');
                        $setting->set('description', '');
                        $setting->set('is_required', true);
                        $setting->set('sortorder', $idx);
                        $setting->set('group', $group->id);
                        $setting->save();
                    }
                    
                    if ($menu = $modx->getObject('modMenu', ['namespace' => 'clientconfig', 'action' => 'home'])) {
                        if ($menu->get('parent') != 'topnav') {
                            $data = $menu->toArray();
                            $data['previous_text'] = $menu->get('text');
                            $data['text'] = 'Контакты';
                            $data['parent'] = 'topnav';
                            $data['description'] = '';
                            $data['icon'] = '';
                            $data['menuindex'] = 99;
                            $data['action_id'] = $data['action'];
                            $response = $modx->runProcessor('system/menu/update', $data);
                            if ($response->isError()) {
                                $modx->log(modX::LOG_LEVEL_INFO, print_r($modx->error->failure($response->getMessage()), true));
                            }
                        }
                    }
                }
            }
			$modx->log(modX::LOG_LEVEL_INFO, 'Run <b>client_config</b> resolver');
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;