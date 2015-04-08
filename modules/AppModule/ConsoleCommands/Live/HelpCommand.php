<?php
namespace Grout\AppModule\ConsoleCommands\Live;

use Grout\AppModule\Types\AppConsoleCommand;

class HelpCommand extends AppConsoleCommand
{
    public function execute()
    {
        $this->response->showCommandLink('ListCommands', 'List all available commands');
        if ($this->app->getConfig()->developmentMode) {
            $this->showDevelopmentCommands();
        }

        $this->showLiveCommands();
    }

    private function showDevelopmentCommands()
    {
        $this->response->showCommandLink('Setup --setup', 'Setup application');
        $this->response->showLink('internal/mails/', 'Show mails');
        $this->response->showLink('internal/logs/', 'Show logs');
    }

    private function showLiveCommands()
    {
        $this->response->showCommandLink('DeployAssets --deploy', 'Deploy component assets');
        $this->response->showCommandLink('ClearCaches', 'Recreate application caches');
        $this->response->showCommandLink('UpdateDatabaseSchema', 'Update database schema');

        $this->response->showLink('internal/errors/', 'Show errors');
    }
}
