<?php
@ini_set('display_errors', 1);
class siteBuilder {
    
    public $config = array(
            'PACKAGE_NAME' => 'site',
            'PACKAGE_VERSION' => '1.0.0',
            'PACKAGE_RELEASE' => 'beta',
            'BUILD_RESOLVERS' => array()
        );
    public $category_attr = array();
    public $modx;
    
    public function __construct($PACKAGE_NAME, $PACKAGE_VERSION, $PACKAGE_RELEASE, $BUILD_RESOLVERS, $ADDONS) {
        if (!empty($PACKAGE_NAME)) {
            $this->config['PACKAGE_NAME'] = $PACKAGE_NAME;
        }
        if (!empty($PACKAGE_VERSION)) {
            $this->config['PACKAGE_VERSION'] = $PACKAGE_VERSION;
        }
        if (!empty($PACKAGE_RELEASE)) {
            $this->config['PACKAGE_RELEASE'] = $PACKAGE_RELEASE;
        }
        if (!empty($BUILD_RESOLVERS) || is_array($BUILD_RESOLVERS)) {
            $this->config['BUILD_RESOLVERS'] = $BUILD_RESOLVERS;
        }
        if (!empty($ADDONS) || is_array($ADDONS)) {
            $this->config['ADDONS'] = $ADDONS;
        }
    }
    
    public function build() {
        set_time_limit(0);
        header('Content-Type:text/html;charset=utf-8');
        
        $builder = $this->prepareBuilder();
        $vehicle = $this->prepareVehicle($builder);
        $this->pack($builder);
    }
    
    public function prepareBuilder() {
        /* define paths */
        $this->config['PACKAGE_ROOT'] = dirname(dirname(__FILE__)) . '/';
        if (file_exists(dirname(dirname(dirname(__FILE__))) . '/core')) {
        	$this->config['MODX_BASE_PATH'] = dirname($this->config['PACKAGE_ROOT']) . '/';
        } else {
        	$this->config['MODX_BASE_PATH'] = dirname(dirname($this->config['PACKAGE_ROOT'])) . '/';
        }
        
        $this->renameDirs();
        
        /* modx connection */
        define('MODX_API_MODE', true);
        require $this->config['MODX_BASE_PATH'] . 'index.php';
        require $this->config['PACKAGE_ROOT'] . '_build/includes/functions.php';
        $this->modx = &$modx;
        $this->modx->setLogLevel(modX::LOG_LEVEL_INFO);
        $this->modx->setLogTarget('ECHO');
        $this->modx->getService('error', 'error.modError');
        $this->modx->loadClass('transport.modPackageBuilder', '', false, true);
        if (!XPDO_CLI_MODE) {
        	echo '<pre>';
        }
        
        /* create builder */
        $builder = new modPackageBuilder($this->modx);
        $builder->createPackage(strtolower($this->config['PACKAGE_NAME']),
                                $this->config['PACKAGE_VERSION'],
                                $this->config['PACKAGE_RELEASE']);
        $this->modx->log(modX::LOG_LEVEL_INFO, 'Created Transport Package.');
        return $builder;
    }
    
    public function renameDirs() {
        /* assets */
        $assets_dir = $this->config['PACKAGE_ROOT'] . 'assets/components/' . strtolower($this->config['PACKAGE_NAME']);
        $assets_old = $this->config['PACKAGE_ROOT'] . 'assets/components/site';
        if (!file_exists($assets_dir) &&
            file_exists($assets_old)) {
            rename($assets_old, $assets_dir);
        }
        
        /* core */
        $core_dir = $this->config['PACKAGE_ROOT'] . 'core/components/' . strtolower($this->config['PACKAGE_NAME']);
        $core_old = $this->config['PACKAGE_ROOT'] . 'core/components/site';
        if (!file_exists($core_dir) &&
            file_exists($core_old)) {
            rename($core_old, $core_dir);
        }
    }
    
    public function prepareVehicle(&$builder) {
        /* create category */
        $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Created category.');
        $category = $this->modx->newObject('modCategory');
        $category->set('category', $this->config['PACKAGE_NAME']);
        
        $this->category_attr[xPDOTransport::UNIQUE_KEY] = 'category';
        $this->category_attr[xPDOTransport::PRESERVE_KEYS] = false;
        $this->category_attr[xPDOTransport::UPDATE_OBJECT] = true;
        $this->category_attr[xPDOTransport::RELATED_OBJECTS] = true;
        
        $this->addPlugins($category);
        $this->addSnippets($category);
        $this->addTemplates($category);
        $this->addChunks($category);
        
        $builder->setPackageAttributes(array(
            'site_category' => $this->config['PACKAGE_NAME'],
            'site_template_name' => $this->config['site_template_name'],
            'ADDONS' => $this->config['ADDONS']
        ));
        $vehicle = $builder->createVehicle($category, $this->category_attr);
        $this->addResolvers($vehicle);
        $builder->putVehicle($vehicle);
        return $vehicle;
    }
    
    public function addPlugins(&$category) {
        $this->category_attr[xPDOTransport::RELATED_OBJECT_ATTRIBUTES]['Plugins'] = array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => 'name',
        );
        $this->category_attr[xPDOTransport::RELATED_OBJECT_ATTRIBUTES]['PluginEvents'] = array(
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => array('pluginid', 'event'),
        );
        $modx = &$this->modx;
        $plugins = include $this->config['PACKAGE_ROOT'] . '_build/data/transport.plugins.php';
        if (!is_array($plugins)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in plugins.');
        } else {
            $category->addMany($plugins);
            $this->modx->log(modX::LOG_LEVEL_INFO, 'Packaged in ' . count($plugins) . ' plugins.');
        }
    }
    
    public function addSnippets(&$category) {
        $this->category_attr[xPDOTransport::RELATED_OBJECT_ATTRIBUTES]['Snippets'] = array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => 'name',
        );
        $modx = &$this->modx;
        $snippets = include $this->config['PACKAGE_ROOT'] . '_build/data/transport.snippets.php';
        if (!is_array($snippets)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in snippets.');
        } else {
            $category->addMany($snippets);
            $this->modx->log(modX::LOG_LEVEL_INFO, 'Packaged in ' . count($snippets) . ' snippets.');
        }
    }
    
    public function addTemplates(&$category) {
        $this->category_attr[xPDOTransport::RELATED_OBJECT_ATTRIBUTES]['Templates'] = array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => 'templatename',
        );
        $modx = &$this->modx;
        $templates = include $this->config['PACKAGE_ROOT'] . '_build/data/transport.templates.php';
        if (!is_array($templates)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in templates.');
        } else {
            $category->addMany($templates);
            $this->modx->log(modX::LOG_LEVEL_INFO, 'Packaged in ' . count($templates) . ' templates.');
            $this->config['site_template_name'] = $this->config['PACKAGE_NAME'];
        }
    }
    
    public function addChunks(&$category) {
        $this->category_attr[xPDOTransport::RELATED_OBJECT_ATTRIBUTES]['Chunks'] = array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => 'name',
        );
        $modx = &$this->modx;
        $chunks = include $this->config['PACKAGE_ROOT'] . '_build/data/transport.chunks.php';
        if (!is_array($chunks)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in chunks.');
        } else {
            $category->addMany($chunks);
            $this->modx->log(modX::LOG_LEVEL_INFO, 'Packaged in ' . count($chunks) . ' chunks.');
        }
    }
    
    public function addResolvers(&$vehicle) {
        /* now pack in resolvers */
        $vehicle->resolve('file', array(
        	'source' => $this->config['PACKAGE_ROOT'] . 'assets/components/' . strtolower($this->config['PACKAGE_NAME']),
        	'target' => "return MODX_ASSETS_PATH . 'components/';",
        ));
        $vehicle->resolve('file', array(
        	'source' => $this->config['PACKAGE_ROOT'] . 'core/components/' . strtolower($this->config['PACKAGE_NAME']),
        	'target' => "return MODX_CORE_PATH . 'components/';",
        ));
        foreach ($this->config['BUILD_RESOLVERS'] as $resolver) {
        	if ($vehicle->resolve('php', array('source' => $this->config['PACKAGE_ROOT'] . '_build/resolvers/' . 'resolve.' . $resolver . '.php'))) {
        		$this->modx->log(modX::LOG_LEVEL_INFO, 'Added resolver "' . $resolver . '" to category.');
        	}
        	else {
        		$this->modx->log(modX::LOG_LEVEL_INFO, 'Could not add resolver "' . $resolver . '" to category.');
        	}
        }
        
        flush();
    }
    
    public function pack(&$builder) {
        /* now pack in the license file, readme and setup options */
        $builder->setPackageAttributes(array(
        	'changelog' => file_get_contents($this->config['PACKAGE_ROOT'] . 'core/components/' . strtolower($this->config['PACKAGE_NAME']) . '/docs/' . 'changelog.txt'),
        	'license' => file_get_contents($this->config['PACKAGE_ROOT'] . 'core/components/' . strtolower($this->config['PACKAGE_NAME']) . '/docs/' . 'license.txt'),
        	'readme' => file_get_contents($this->config['PACKAGE_ROOT'] . 'core/components/' . strtolower($this->config['PACKAGE_NAME']) . '/docs/' . 'readme.txt'),
        	'setup-options' => array(
                'source' => $this->config['PACKAGE_ROOT'] . '_build/includes/setup.options.php',
        	),
        ));
        $this->modx->log(modX::LOG_LEVEL_INFO, 'Added package attributes.');
        
        /* zip up package */
        $this->modx->log(modX::LOG_LEVEL_INFO, 'Packing up transport package zip...');
        $builder->pack();
        
        $signature = $builder->getSignature();
        if (!empty($_GET['download'])) {
        	echo '<script>document.location.href = "/core/packages/' . $signature . '.transport.zip' . '";</script>';
        }
        
        $this->modx->log(modX::LOG_LEVEL_INFO, "Done.\n<br />Completed\n");
        if (!XPDO_CLI_MODE) {
        	echo '</pre>';
        }
    }
}
return 'siteBuilder';
