<?php
use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Bootstrap;
use Cyantree\Grout\App\ConsoleBootstrap;
use Cyantree\Grout\AutoLoader;

// Catch startup error
$startupError = error_get_last();
while(ob_get_level()){
    ob_end_clean();
}

$isConsole = php_sapi_name() == 'cli';

// Update configuration to fit your setup
$basePath = realpath(dirname(__FILE__)) . '/';
$init = array(
    'frameworkPath' => $basePath,
    'dataPath' => $basePath . 'data/',

    'bootstrap' => array(
        'module' => 'AppModule',
        'config' => array(
            'config' => null // Enter your desired configuration name
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

if ($isConsole) {
    $bootstrap = new ConsoleBootstrap($app);
    $bootstrap->frameworkPath = $init['frameworkPath'];
    $request = $bootstrap->init();

} else {
    $bootstrap = new Bootstrap($app);
    $bootstrap->frameworkPath = $init['frameworkPath'];
    $bootstrap->usesModRewrite = true;
    $bootstrap->checkForMagicQuotes = true;
    $request = $bootstrap->init();
}

$request->config->set('startupError', $startupError);

$app->init();

// Import bootstrap module and process request
$app->importModule($init['bootstrap']['module'], null, $init['bootstrap']['config']);

$response = $app->processRequest($request);
$app->destroy();

if (!$isConsole) {
    $response->postHeaders();
}

echo $response->content;