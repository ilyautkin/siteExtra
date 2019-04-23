<?php

if ($object->xpdo) {
	/** @var modX $modx */
	$modx =& $object->xpdo;
    
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:
		    if (!in_array('CKEditor', $options['install_addons'])) return true;
		    
            $path = MODX_MANAGER_PATH . 'assets/components/ckeditor/ckeditor/plugins/';
            $file = 'base64image.zip';
            
            if (!file_exists($path . 'base64image')) {
		        $modx->log(modX::LOG_LEVEL_INFO, 'Run <b>CKEditor setup</b>');
                if (!file_exists($path . $file)) {
                    $contents = file_get_contents('https://github.com/nmmf/base64image/archive/master.zip');
                    file_put_contents($path . $file, $contents);
                }
                
                $zip = new ZipArchive;
                $res = $zip->open($path . $file);
                if ($res === TRUE) {
                    $zip->extractTo($path);
                    $zip->close();
                    if (file_exists($path . 'base64image-master')) {
                        rename($path . 'base64image-master', $path . 'base64image');
                    }
                    if (!$tmp = $modx->getObject('modSystemSetting', array('key' => 'ckeditor.toolbar'))) {
                        $tmp = $modx->newObject('modSystemSetting');
                    }
                    $tmp->fromArray(array(
                        'namespace' => 'ckeditor',
                        'area'      => 'general',
                        'xtype'     => 'textfield',
                        'value'     => '[["Source"],["Bold","Italic","Underline","Striketrough","Subscript","Superscript"],["NumberedList","BulletedList","Blockquote"],["JustifyLeft","JustifyCenter","JustifyRight"],["Link","Unlink"],["base64image","Table","HorizontalRule"],["Format","TextColor","Maximize"]]',
                        'key'       => 'ckeditor.toolbar',
                    ), '', true, true);
                    $tmp->save();
                    
                    $plugins = ['base64image'];
                    if ($tmp = $modx->getObject('modSystemSetting', array('key' => 'ckeditor.extra_plugins'))) {
                        if ($tmp->get('value')) {
                            $value = explode(',', $tmp->get('value'));
                            if (!empty($value)) {
                                $plugins = array_unique(array_merge($value, $plugins));
                            }
                        }
                    }
                    $tmp->fromArray(array(
                        'namespace' => 'ckeditor',
                        'area'      => 'general',
                        'xtype'     => 'textfield',
                        'value'     => implode(',', $plugins),
                        'key'       => 'ckeditor.extra_plugins',
                    ), '', true, true);
                    $tmp->save();
                }
            }
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;