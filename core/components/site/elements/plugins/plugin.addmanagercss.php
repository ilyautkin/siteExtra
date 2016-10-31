<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case 'OnManagerPageInit':
        $modx->regClientCSS($modx->getOption('assets_url') .
            'components/' . $modx->getOption('site_folder_name') .
            '/mgr/style.css');
        break;
    default:
        break;
}
