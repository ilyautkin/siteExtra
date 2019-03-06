<?php
/** @var modX $this->modx */
/** @var array $sources */

$chunks = array();

$tmp = array(
    'head' => array(
        'file' => 'head',
        'description' => ''
    ),
    'menu' => array(
        'file' => 'menu',
        'description' => ''
    ),
    'child_list' => array(
        'file' => 'child_list',
        'description' => ''
    ),
    'header' => array(
        'file' => 'header',
        'description' => ''
    ),
    'content' => array(
        'file' => 'content',
        'description' => ''
    ),
    'content_default' => array(
        'file' => 'content_default',
        'description' => ''
    ),
    'content_main' => array(
        'file' => 'content_main',
        'description' => ''
    ),
    'content_spec' => array(
        'file' => 'content_spec',
        'description' => ''
    ),
    'content_spec_list' => array(
        'file' => 'content_spec_list',
        'description' => ''
    ),
    'aside' => array(
        'file' => 'aside',
        'description' => ''
    ),
    'footer' => array(
        'file' => 'footer',
        'description' => ''
    ),
    'contact_form' => array(
        'file' => 'contact_form',
        'description' => ''
    ),
    'form.contact_form' => array(
        'file' => 'form_contact_form',
        'description' => ''
    ),
    'tpl.contact_form' => array(
        'file' => 'tpl_contact_form',
        'description' => ''
    ),
    'gallery' => array(
        'file' => 'gallery',
        'description' => ''
    ),
    'block.gallery' => array(
        'file' => 'block_gallery',
        'description' => ''
    ),
    'scripts' => array(
        'file' => 'scripts',
        'description' => ''
    ),
);
$setted = false;
foreach ($tmp as $k => $v) {
    
    /** @var modchunk $chunk */
    $chunk = $this->modx->newObject('modChunk');
    $chunk->fromArray(array(
        'name' => $k,
        'category' => 0,
        'description' => @$v['description'],
        'content' => file_get_contents($this->config['PACKAGE_ROOT'] . 'core/components/'.strtolower($this->config['PACKAGE_NAME']).'/elements/chunks/chunk.' . $v['file'] . '.html'),
        'static' => false,
        //'source' => 1,
        //'static_file' => 'core/components/'.strtolower($this->config['PACKAGE_NAME']).'/elements/chunks/chunk.' . $v['file'] . '.html',
    ), '', true, true);
    $chunks[] = $chunk;
}
unset($tmp, $properties);

return $chunks;