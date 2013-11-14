<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{
        protected function _initRouter(){
            if (PHP_SAPI == 'cli')
            {
                $this->bootstrap ('frontcontroller');
                $front = $this->getResource('frontcontroller');
                $front->setRouter (new Application_Router_Cli ());
                $front->setRequest (new Zend_Controller_Request_Simple ());
            }
	}
	protected function _initView(){

		// Initialize view
		$view = new Zend_View();
		$view->env = APPLICATION_ENV;
		$view->setScriptPath(APPLICATION_PATH.'/views/default/');

		// Add it to the ViewRenderer
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
		    'ViewRenderer'
		);
		$viewRenderer->setView($view);

		// add helper path
		$view->addHelperPath('Jien/View/Helper/', 'Jien_View_Helper');
		$view->addHelperPath('My/View/Helper/', 'My_View_Helper');

		// Return it, so that it can be stored by the bootstrap
		return $view;
	}

	protected function _initConfig(){

		ini_set('log_errors', 1);
		ini_set('error_log', getcwd() . '/../logs/error_log');

	    $config = new Zend_Config($this->getOptions(), true);
	    Zend_Registry::set('config', $config);
	    Zend_Registry::set('params', array());

	    // set all settings to global
	    foreach($config->settings AS $name=>$value){
	    	define(strtoupper($name), $value);
	    }

	    /** MEM CACHE **/
		 // try to set caching via memcache
		try {
		    $frontendOptions = new Zend_Cache_Core(array(
				'caching' => true,
				'cache_id_prefix' => 'memcache',
				'automatic_serialization' => true,
			));

			$memcacheHosts = array();
			$memcacheServers = array();
			if(strstr(MEMCACHE_HOST,',')){
				$memcacheHosts = explode(",", MEMCACHE_HOST);
			}else{
				$memcacheHosts[] = trim(MEMCACHE_HOST);
			}

			foreach($memcacheHosts AS $host){
				$memcacheServers[] = array(
					"host"	=>	$host,
					"port"	=>	MEMCACHE_PORT,
				);
			}
			$backendOptions  = new Zend_Cache_Backend_Memcached(array(
				'servers' => $memcacheServers,
				'compression' => true
			));
			$cache = Zend_Cache::factory( $frontendOptions, $backendOptions);

			// test memcache
			$cache->save(1, 'initialized');
			$memcache_test = $cache->load('initialized');
			if(empty($memcache_test)){
				$memcache_enabled = 0;
			}else{
				$memcache_enabled = 1;
			}
		}catch(Exception $e){
			$memcache_enabled = 0;
		}

		if($memcache_enabled == 0){
			/** FILE CACHE **/
	    	// set caching via files
		    $frontendOptions = array(
			    'automatic_serialization' => true,
			    'lifetime'	=>	500,
			);
			$backendOptions  = array(
			    'cache_dir' => APPLICATION_PATH . '/../cache'
			);
			$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

		}else{

			// save session to memcache
		    ini_set('session.save_handler', 'memcache');
		    $memcachePaths = array();
		    foreach($memcacheServers AS $server){
		    	$memcachePaths[] = "tcp://{$server['host']}:{$server['port']}?persistent=1";
		    }
			ini_set('session.save_path', implode(", ", $memcachePaths));

		}

	    // set cache to registry
	    Zend_Registry::set('cache', $cache);
        Zend_Registry::set('timers', array());

	    return $config;
	}

	protected function _initSession(){
		Zend_Session::start();
	}

	protected function _initController(){

	}

	protected function _initDb(){

		// gets our multi db instances
        $this->bootstrap('multidb');
        $dbr = $this->getResource('multidb');

        // set to registry, can also get our default db adapter by doing: $db = Zend_Db_Table::getDefaultAdapter();
        Zend_Registry::set('db', $dbr->getDb('db'));

        // for time benchmarking
        Zend_Registry::set('timers', array());

	}

	protected function _initAttributeExOpenIDPath() {
        /*$autoLoader = Zend_Loader_Autoloader::getInstance();

        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath' => APPLICATION_PATH,
            'namespace' => 'My_',
        ));

        $resourceLoader->addResourceType('openidextension', 'openid/extension/', 'OpenId_Extension');
        $resourceLoader->addResourceType('authAdapter', 'auth/adapter', 'Auth_Adapter');

        $autoLoader->pushAutoloader($resourceLoader);*/
    }

}

