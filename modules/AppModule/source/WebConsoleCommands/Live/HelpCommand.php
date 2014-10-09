<?php
namespace Grout\AppModule\WebConsoleCommands\Live;

use Grout\AppModule\Types\AppWebConsoleCommand;

class HelpCommand extends AppWebConsoleCommand
{
    public function execute()
    {
        if ($this->app->getConfig()->developmentMode) {
            $this->showDevelopmentCommands();
        }

        $this->showLiveCommands();
    }

    private function showDevelopmentCommands()
    {
        $ui = $this->factory()->ui();

        $this->show($this->generateCommandLink('Setup --setup', 'Setup application'), true);
        $this->show($ui->link('internal/mails/', 'Show mails', '_blank'), true);
        $this->show($ui->link('internal/logs/', 'Show logs', '_blank'), true);
    }

    private function showLiveCommands()
    {
        $ui = $this->factory()->ui();

        $this->show($this->generateCommandLink('ClearCaches', 'Recreate application caches'), true);
        $this->show($this->generateCommandLink('UpdateDatabaseSchema', 'Update database schema'), true);

        $this->show($ui->link('internal/errors/', 'Show errors', '_blank'), true);


    }
}
