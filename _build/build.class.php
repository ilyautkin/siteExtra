<?php
class siteBuilder {
    
    public $config = array(
            'PACKAGE_NAME' => 'site',
            'PACKAGE_VERSION' => '1.0.0',
            'PACKAGE_RELEASE' => 'beta',
            'BUILD_RESOLVERS' => array()
        );
    public $modx;
    
    public function __construct($PACKAGE_NAME, $PACKAGE_VERSION, $PACKAGE_RELEASE, $BUILD_RESOLVERS) {
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
        
        /* create category vehicle */
        $attr = array(
        	xPDOTransport::UNIQUE_KEY => 'category',
        	xPDOTransport::PRESERVE_KEYS => false,
        	xPDOTransport::UPDATE_OBJECT => true,
        	xPDOTransport::RELATED_OBJECTS => true,
        );
        $vehicle = $builder->createVehicle($category, $attr);
        $this->addResolvers($vehicle);
        $builder->putVehicle($vehicle);
        return $vehicle;
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
