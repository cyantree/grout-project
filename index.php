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

$applicationPath = './';
$dataPath = $applicationPath . 'data/';

$isConsole = php_sapi_name() == 'cli';

// Update configuration to fit your setup
$applicationPath = realpath(__DIR__ . '/' . $applicationPath) . '/';

chdir($applicationPath);

// Init auto loader
require_once($applicationPath . 'vendor/autoload.php');
AutoLoader::init();
if(is_dir($applicationPath . 'source/')){
    AutoLoader::registerNamespace('', $applicationPath . 'source/');
}

// Setup request and application
App::initEnvironment();
$app = new App();
$app->dataPath = realpath(__DIR__ . '/' . $dataPath) . '/';

if ($isConsole) {
    $bootstrap = new ConsoleBootstrap($app);
    $bootstrap->applicationPath = $applicationPath;
    $request = $bootstrap->init();

} else {
    $bootstrap = new Bootstrap($app);
    $bootstrap->applicationPath = $applicationPath;
    $bootstrap->usesModRewrite = true;
    $bootstrap->checkForMagicQuotes = true;
    $request = $bootstrap->init();
}

$request->config->set('startupError', $startupError);

$app->init();

// Import bootstrap module and process request
$app->importModule('AppModule');

$response = $app->processRequest($request);
$app->destroy();

if (!$isConsole) {
    $response->postHeaders();
}

echo $response->content;