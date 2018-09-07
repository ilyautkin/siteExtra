<?php
/** @var modX $this->modx */
/** @var array $sources */

$snippets = array();

$tmp = array(
    'rtrim' => array(
        'file' => 'rtrim',
        'description' => ''
    ),
);

foreach ($tmp as $k => $v) {
    /** @var modsnippet $snippet */
    $snippet = $this->modx->newObject('modSnippet');
    $snippet->fromArray(array(
        'name' => $k,
        'category' => 0,
        'description' => @$v['description'],
        'snippet' => getSnippetContent($this->config['PACKAGE_ROOT'] . 'core/components/'.strtolower($this->config['PACKAGE_NAME']).'/elements/snippets/snippet.' . $v['file'] . '.php'),
        'static' => false,
        //'source' => 1,
        //'static_file' => 'core/components/'.strtolower($this->config['PACKAGE_NAME']).'/elements/snippets/snippet.' . $v['file'] . '.php',
    ), '', true, true);

    $snippets[] = $snippet;
}
unset($tmp, $properties);

return $snippets;
