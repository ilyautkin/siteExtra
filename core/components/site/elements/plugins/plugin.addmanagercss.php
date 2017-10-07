<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case 'OnManagerPageInit':
        $modx->regClientCSS($modx->getOption('assets_url') .
            'components/SITE_FOLDER_NAME/mgr/style.css');
        break;
    default:
        break;
}
