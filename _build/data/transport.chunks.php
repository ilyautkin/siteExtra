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