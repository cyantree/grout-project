<?php

// Catch startup error
use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Bootstrap;
use Cyantree\Grout\AutoLoader;

include('config.php');
chdir($init['frameworkPath']);

$currentDir = realpath(dirname(__FILE__)) . '/';

// Init auto loader
require_once($init['frameworkPath'] . 'vendor/autoload.php');
AutoLoader::init();
if(is_dir($init['frameworkPath'] . 'source/')){
	AutoLoader::registerNamespace('', $init['frameworkPath'] . 'source/');
}

AutoLoader::registerNamespace('', $currentDir . 'source/');

// Setup request and application
App::initEnvironment();
$app = new App();
$app->dataPath = $init['dataPath'];
$app->id = $init['appId'];
$app->url = $init['baseUrl'];
$app->publicUrl = $app->url;
$app->path = $currentDir . '../';
$app->publicPath = $app->path;
$app->dataPath = $init['dataPath'];

$app->init();

// Import bootstrap module and process request
$app->importModule($init['bootstrap']['module'], null, $init['bootstrap']['config']);
