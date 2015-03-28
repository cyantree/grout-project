<?php
use Cyantree\Grout\App\App;
use Cyantree\Grout\App\WebBootstrap;
use Cyantree\Grout\App\ConsoleBootstrap;

$timeStarted = microtime(true);

// Catch startup error
$startupError = error_get_last();
while (ob_get_level()) {
    ob_end_clean();
}

// Update configuration to fit your setup
$applicationPath = '../';

chdir(__DIR__ . '/' . $applicationPath);


// Init auto loader
require_once('vendor/autoload.php');

// Setup request and application
App::initEnvironment();
$app = new App(null, $timeStarted);

if (php_sapi_name() == 'cli') {
    $bootstrap = new ConsoleBootstrap();

} else {
    $bootstrap = new WebBootstrap();
    $bootstrap->usesModRewrite = true;
    $bootstrap->checkForMagicQuotes = true;
}

$bootstrap->app = $app;
$bootstrap->entryFilePath = __FILE__;

$bootstrap->initApp();
$request = $bootstrap->createRequest();

$request->config->set('startupError', $startupError);

$app->init();

// Import bootstrap module and process request
$app->importModule('AppModule');

$response = $app->processRequest($request);
$app->destroy();

$response->postHeaders();

echo $response->content;
