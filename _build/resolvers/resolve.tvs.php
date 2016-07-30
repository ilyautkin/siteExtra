<?php

/** @var $modx modX */
if (!$modx = $object->xpdo AND !$object->xpdo instanceof modX) {
    return true;
}

/** @var $options */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

        $name = 'img';
        $templateId = $modx->getOption('default_template');
        if (!$tv = $modx->getObject('modTemplateVar', array('name' => $name))) {
            $tv = $modx->newObject('modTemplateVar');
        }
        $tv->fromArray(array(
            'name'         => $name,
            'type'         => 'fastuploadtv',
            'caption'      => 'Изображение',
            'input_properties' => array(
                                    "path" => "assets/images/{d}-{m}-{y}/",
                                    "prefix" => "",
                                    "MIME" => "",
                                    "showValue" => false,
                                    "showPreview" => true
                                ),
        ));
        $tv->save();
        
        foreach ($modx->getCollection('modTemplate') as $template) {
            $templateId = $template->id;
            if (!$tvt = $modx->getObject('modTemplateVarTemplate', array('tmplvarid' => $tv->id, 'templateid' => $templateId))) {
                $record = array('tmplvarid' => $tv->id, 'templateid' => $templateId);
                $keys = array_keys($record);
                $fields = '`' . implode('`,`', $keys) . '`';
                $placeholders = substr(str_repeat('?,', count($keys)), 0, -1);
                $sql = "INSERT INTO {$modx->getTableName('modTemplateVarTemplate')} ({$fields}) VALUES ({$placeholders});";
                $modx->prepare($sql)->execute(array_values($record));
            }
        }
        
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return true;