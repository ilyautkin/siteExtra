<?php
/** @var modX $this->modx */
/** @var array $sources */

$templates = array();

$tmp = array(
    'siteTemplate' => array(
        'file' => 'sitetemplate',
        'description' => ''
    )
);

foreach ($tmp as $k => $v) {
    
    /** @var modtemplate $template */
    $template = $this->modx->newObject('modTemplate');
    $template->fromArray(array(
        'templatename' => $k,
        'category' => 0,
        'description' => @$v['description'],
        'content' => file_get_contents($this->config['PACKAGE_ROOT'] . 'core/components/'.strtolower($this->config['PACKAGE_NAME']).'/elements/templates/template.' . $v['file'] . '.html'),
        'static' => false,
        //'source' => 1,
        //'static_file' => 'core/components/'.strtolower($this->config['PACKAGE_NAME']).'/elements/templates/template.' . $v['file'] . '.html',
    ), '', true, true);

    $templates[] = $template;
}
unset($tmp, $properties);

return $templates;