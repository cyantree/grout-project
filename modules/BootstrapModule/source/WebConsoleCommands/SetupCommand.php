<?php
namespace Grout\BootstrapModule\WebConsoleCommands;

use Grout\Cyantree\WebConsoleModule\Types\WebConsoleCommand;

class SetupCommand extends WebConsoleCommand
{
    private $_error = false;
    public function execute()
    {
        if (!$this->args->has('setup')) {
            $this->show('Run Setup --setup to run the setup.');
            $this->show('--delete-data: Deletes all data storages.');
            return;
        }

        $this->show('The installation will be checked...');
        $this->_checkInstallation();

        if ($this->_error) {
            $this->show('An error occurred while checking the installation.');
            return;
        }

        $this->show('The application will be set up.');
        $this->_setup();

        if ($this->_error) {
            $this->show('An error occurred while setting up the application.');
            return;
        }

        $this->show('The application has been set up.');
    }

    private function _checkInstallation()
    {
        if (!is_dir($this->app->dataPath) || !is_writable($this->app->dataPath)) {
            $this->show('Error: '.$this->app->dataPath.' should be a writable directory. Its contents also have to be writable.');
            $this->_error = true;
            return;
        }
    }

    private function _setup()
    {
        $this->app->cacheStorage->deleteAllStorages();

        if ($this->args->has('delete-data')) {
            $this->app->dataStorage->deleteAllStorages();
        }
    }
}