<?php
namespace Grout\AppModule\WebConsoleCommands\Live;

use Grout\AppModule\Types\AppWebConsoleCommand;

class HelpCommand extends AppWebConsoleCommand
{
    public function execute()
    {
        if ($this->app->getConfig()->developmentMode) {
            $this->_showDevelopmentCommands();
        }

        $this->_showLiveCommands();
    }

    private function _showDevelopmentCommands()
    {
        $ui = $this->factory()->appUi();
        $config = $this->app->getConfig();

        $this->show($this->generateCommandLink('Setup --setup', 'Setup application'), true);
        $this->show($ui->link('mails/' . $config->internalAccessKey . '/', 'Show mails', '_blank'), true);
        $this->show($ui->link('logs/' . $config->internalAccessKey . '/', 'Show logs', '_blank'), true);
    }

    private function _showLiveCommands()
    {
        $ui = $this->factory()->appUi();
        $config = $this->app->getConfig();

        $this->show($this->generateCommandLink('ClearCaches', 'Recreate application caches'), true);
        $this->show($this->generateCommandLink('UpdateDatabaseSchemaCommand', 'Update database schema'), true);

        $this->show($ui->link('errors/' . $config->internalAccessKey . '/', 'Show errors', '_blank'), true);


    }
}