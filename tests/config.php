<?php
// Update configuration to fit your setup
$init = array(
    'appId' => null,
    'frameworkPath' => realpath(dirname(__FILE__).'/..').'/',
    'dataPath' => realpath(dirname(__FILE__).'/../data').'/',
    'baseUrl' => 'http://localhost/grout-app/',

    'bootstrap' => array(
        'module' => 'BootstrapModule',
        'config' => array(
            'config' => 'TestConfig',

            'mainModules' => array(
                array(
                    'type' => 'TestModule',
                    'url' => null,
                    'config' => null,
                    'priority' => 0
                )
            )
        )
    )
);