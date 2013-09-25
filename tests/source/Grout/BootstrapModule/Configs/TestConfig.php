<?php
namespace Grout\BootstrapModule\Configs;

class TestConfig extends BootstrapDefaultConfig
{
    function __construct()
    {
        $this->errorReporting = false;
    }
}