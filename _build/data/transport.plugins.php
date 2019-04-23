<?php
/** @var modX $this->modx */
/** @var array $sources */

$plugins = array();

$tmp = array(
    'addManagerCss' => array(
        'file' => 'addmanagercss',
        'description' => '',
        'events' => array(
            'OnManagerPageInit' => array()
        )
    ),
    'siteRedirect' => array(
        'file' => 'siteredirect',
        'description' => '',
        'events' => array(
            'OnHandleRequest' => array()
        )
    ),
    'convertBase64Images' => array(
        'file' => 'convertbase64images',
        'description' => '',
        'events' => array(
            'OnDocFormSave' => array()
        )
    )
);

foreach ($tmp as $k => $v) {
    /** @var modplugin $plugin */
    $plugin = $this->modx->newObject('modPlugin');
    $plugin->fromArray(array(
        'name' => $k,
        'category' => 0,
        'disabled' => $k == 'siteRedirect' ? 1 : 0,
        'description' => @$v['description'],
        'plugincode' => getSnippetContent($this->config['PACKAGE_ROOT'] . 'core/components/'.strtolower($this->config['PACKAGE_NAME']).'/elements/plugins/plugin.' . $v['file'] . '.php'),
        'static' => false,
        //'source' => 1,
        //'static_file' => 'core/components/'.strtolower($this->config['PACKAGE_NAME']).'/elements/plugins/plugin.' . $v['file'] . '.php',
    ), '', true, true);

    $events = array();
    if (!empty($v['events'])) {
        foreach ($v['events'] as $k2 => $v2) {
            /** @var modPluginEvent $event */
            $event = $this->modx->newObject('modPluginEvent');
            $event->fromArray(array_merge(
                array(
                    'event' => $k2,
                    'priority' => 0,
                    'propertyset' => 0,
                ), $v2
            ), '', true, true);
            $events[] = $event;
        }
        unset($v['events']);
    }

    if (!empty($events)) {
        $plugin->addMany($events);
    }
    $plugins[] = $plugin;
}
unset($tmp, $properties);

return $plugins;