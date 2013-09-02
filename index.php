<?php
use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Bootstrap;
use Cyantree\Grout\AutoLoader;

// Catch startup error
$startupError = error_get_last();
while(ob_get_level()){
    ob_end_clean();
}

// Update configuration to fit your setup
$init = array(
    'appId' => null,
    'frameworkPath' => realpath(dirname(__FILE__).'/.').'/',
    'dataPath' => realpath(dirname(__FILE__).'/data').'/',

    'bootstrap' => array(
        'module' => 'BootstrapModule',
        'config' => array(
            'mainModules' => array(
                array(
                    'module' => 'TestModule',
                    'url' => null,
                    'config' => null,
                    'priority' => 0
                )
            )
        )
    )
);
chdir($init['frameworkPath']);

// Init auto loader
require_once($init['frameworkPath'] . 'vendor/autoload.php');
AutoLoader::init();
if(is_dir('source/')){
	AutoLoader::registerNamespace('', 'source/');
}

// Setup request and application
App::initEnvironment();
$app = new App();
$app->dataPath = $init['dataPath'];
$app->id = $init['appId'];

$bootstrap = new Bootstrap($app);
$request = $bootstrap->init($init['frameworkPath'], true);
$request->config->set('startupError', $startupError);

$app->init();

// Import bootstrap module and process request
$app->importModule($init['bootstrap']['module'], null, $init['bootstrap']['config']);

$response = $app->processRequest($request);
$app->destroy();

$response->postHeaders();
echo $response->content;