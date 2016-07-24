<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case 'OnManagerPageInit':
        $modx->regClientCSS($modx->getOption('assets_url') . 'components/site/mgr/style.css');
        break;
    default:
        break;
}
