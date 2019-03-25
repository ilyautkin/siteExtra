<?php

/** @var $modx modX */
if (!$modx = $object->xpdo AND !$object->xpdo instanceof modX) {
    return true;
}

/** @var $options */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        if (isset($options['site_category']) && $options['site_category']) {
            if ($category = $modx->getObject('modCategory', array('category' => $options['site_category']))) {
                $cat_id = $category->get('id');
            } else {
                $cat_id = 0;
            }
        } else {
            $cat_id = 0;
        }
        
        $tvs = array();
        
        $name = 'img';
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => $name))) {
            $tv = $modx->newObject('modTemplateVar');
            if (in_array('FastUploadTV', $options['install_addons'])) {
                $image_tv_type = 'fastuploadtv';
            } else {
                $image_tv_type = 'image';
            }
            if ($modx->getOption('cultureKey') == 'ru') {
                $caption = 'Изображение';
            } else {
                $caption = 'Image';
            }
            $tv->fromArray(array(
                'name'         => $name,
                'type'         => $image_tv_type,
                'caption'      => $caption, //'Изображение',
                'category'     => $cat_id,
                'input_properties' => array(
                                        "path" => "assets/images/{d}-{m}-{y}/",
                                        "prefix" => "{rand}-",
                                        "MIME" => "",
                                        "showValue" => false,
                                        "showPreview" => true
                                    ),
            ));
            $tv->save();
            $tvs[] = $tv->get('id');
        }
        
        $name = 'show_on_page';
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => $name))) {
            $tv = $modx->newObject('modTemplateVar');
            if ($modx->getOption('cultureKey') == 'ru') {
                $caption = 'Отображать на странице';
            } else {
                $caption = 'Display on page';
            }
            $tv->fromArray(array(
                'name'         => $name,
                'type'         => 'checkbox',
                'caption'      => $caption, //'Отображать на странице',
                'category'     => $cat_id,
                'elements'     => 'Дочерние ресурсы==children||Контент==content||Галерею==gallery',
                'default_text' => 'children||content||gallery',
                'display'      => 'delim',
                'output_properties' => array(
                                'delimiter' => '||'
                    )
            ));
            $tv->save();
            $tvs[] = $tv->get('id');
        }
        
        /* Перенесено в ClientConfig
        
        $name = 'address';
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => $name))) {
            $tv = $modx->newObject('modTemplateVar');
            $tv->fromArray(array(
                'name'         => $name,
                'type'         => 'text',
                'caption'      => $caption, //'Адрес',
                'category'     => $cat_id
            ));
            $tv->save();
            $tvs[] = $tv->get('id');
        }
        
        $name = 'phone';
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => $name))) {
            $tv = $modx->newObject('modTemplateVar');
            $tv->fromArray(array(
                'name'         => $name,
                'type'         => 'text',
                'caption'      => $caption, //'Телефон',
                'category'     => $cat_id
            ));
            $tv->save();
            $tvs[] = $tv->get('id');
        }
        
        $name = 'email';
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => $name))) {
            $tv = $modx->newObject('modTemplateVar');
            $tv->fromArray(array(
                'name'         => $name,
                'type'         => 'text',
                'caption'      => $caption, //'E-mail',
                'category'     => $cat_id
            ));
            $tv->save();
            $tvs[] = $tv->get('id');
        }
        */
        
        $name = 'keywords';
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => $name))) {
            $tv = $modx->newObject('modTemplateVar');
            if ($modx->getOption('cultureKey') == 'ru') {
                $caption = 'Keywords';
            } else {
                $caption = 'Keywords';
            }
            $tv->fromArray(array(
                'name'         => $name,
                'type'         => 'text',
                'caption'      => $caption, //'Keywords',
                'category'     => $cat_id
            ));
            $tv->save();
            $tvs[] = $tv->get('id');
        }
        
        $name = 'subtitle';
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => $name))) {
            $tv = $modx->newObject('modTemplateVar');
            if ($modx->getOption('cultureKey') == 'ru') {
                $caption = 'Подпись';
            } else {
                $caption = 'Subtitle';
            }
            $tv->fromArray(array(
                'name'         => $name,
                'type'         => 'text',
                'caption'      => $caption, //'Подпись',
                'category'     => $cat_id
            ));
            $tv->save();
            $tvs[] = $tv->get('id');
        }
        
        if (in_array('MIGX', $options['install_addons'])) {
            $name = 'elements';
            if (!$tv = $modx->getObject('modTemplateVar', array('name' => $name))) {
                $tv = $modx->newObject('modTemplateVar');
                if ($modx->getOption('cultureKey') == 'ru') {
                    $caption = 'Элементы';
                } else {
                    $caption = 'Elements';
                }
                if ($modx->getOption('cultureKey') == 'ru') {
                    $tv->fromArray(array(
                        'name'         => $name,
                        'type'         => 'migx',
                        'caption'      => $caption, //'Элементы',
                        'category'     => $cat_id,
                        'input_properties' => array(
                                                "formtabs" => '[{"caption":"Элемент","fields":[{"field":"title","caption":"Заголовок"},{"field":"subtitle","caption":"Подзаголовок"},{"field":"img","caption":"Изображение","inputTV":"img"},{"field":"content","caption":"Контент","inputTVtype":"richtext"}]}]',
                                                "columns" => '[{"header":"Изображение","dataIndex":"img","width":200,"renderer":"this.renderImage"},{"header":"Содержимое","dataIndex":"title","width":400}]'
                                            ),
                    ));
                } else {
                    $tv->fromArray(array(
                        'name'         => $name,
                        'type'         => 'migx',
                        'caption'      => $caption, //'Элементы',
                        'category'     => $cat_id,
                        'input_properties' => array(
                                                "formtabs" => '[{"caption":"Element","fields":[{"field":"title","caption":"Title"},{"field":"subtitle","caption":"Subtitle"},{"field":"img","caption":"Image","inputTV":"img"},{"field":"content","caption":"Content","inputTVtype":"richtext"}]}]',
                                                "columns" => '[{"header":"Image","dataIndex":"img","width":200,"renderer":"this.renderImage"},{"header":"Content","dataIndex":"title","width":400}]'
                                            ),
                    ));
                }
                $tv->save();
                $tvs[] = $tv->get('id');
            }
        }
        
        foreach ($modx->getCollection('modTemplate') as $template) {
            $templateId = $template->id;
            foreach ($tvs as $k => $tvid) {
                if (!$tvt = $modx->getObject('modTemplateVarTemplate', array('tmplvarid' => $tvid, 'templateid' => $templateId))) {
                    $record = array('tmplvarid' => $tvid, 'templateid' => $templateId);
                    $keys = array_keys($record);
                    $fields = '`' . implode('`,`', $keys) . '`';
                    $placeholders = substr(str_repeat('?,', count($keys)), 0, -1);
                    $sql = "INSERT INTO {$modx->getTableName('modTemplateVarTemplate')} ({$fields}) VALUES ({$placeholders});";
                    $modx->prepare($sql)->execute(array_values($record));
                }
            }
        }
        
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return true;