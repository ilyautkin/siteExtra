<?php

/** @var $modx modX */
if (!$modx = $object->xpdo AND !$object->xpdo instanceof modX) {
    return true;
}

/** @var $options */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

        if (isset($_SESSION['site_category']) && $_SESSION['site_category']) {
            if ($category = $modx->getObject('modCategory', array('category' => $_SESSION['site_category']))) {
                $cat_id = $category->get('id');
                unset($_SESSION['site_category']);
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
        }
        $tv->fromArray(array(
            'name'         => $name,
            'type'         => 'fastuploadtv',
            'caption'      => 'Изображение',
            'category'     => $cat_id,
            'input_properties' => array(
                                    "path" => "assets/images/{d}-{m}-{y}/",
                                    "prefix" => "",
                                    "MIME" => "",
                                    "showValue" => false,
                                    "showPreview" => true
                                ),
        ));
        $tv->save();
        $tvs[] = $tv->get('id');
        
        $name = 'show_child';
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => $name))) {
            $tv = $modx->newObject('modTemplateVar');
        }
        $tv->fromArray(array(
            'name'         => $name,
            'type'         => 'checkbox',
            'caption'      => 'Отображение на странице',
            'category'     => $cat_id,
            'elements'     => 'Дочерние ресурсы==1',
            'display'      => 'default',
            'default_text' => '1'
        ));
        $tv->save();
        $tvs[] = $tv->get('id');
        
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