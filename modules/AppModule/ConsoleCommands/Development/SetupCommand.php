<?php
namespace Grout\AppModule\ConsoleCommands\Development;

use Doctrine\ORM\Tools\SchemaTool;
use Grout\AppModule\Types\AppConsoleCommand;

class SetupCommand extends AppConsoleCommand
{
    public function execute()
    {
        if (!$this->request->args->get('setup')) {
            $this->showHelp();

        } else {
            $this->setup();
        }
    }

    private function showHelp()
    {
        $this->response->showInfo('Execute with --setup to run setup.');

        $this->response->showInfo('--drop-database: Drops whole database and recreates all tables. Use with caution!');
    }

    private function setup()
    {
        $this->response->showInfo('Application will be set up.');

        $factory = $this->factory();

        $doctrine = $factory->doctrine();

        $this->app->dataStorage->deleteAllStorages();
        $this->app->cacheStorage->deleteAllStorages();
        $this->app->dataStorage->warmUp();
        $this->app->cacheStorage->warmUp();

        $schema = new SchemaTool($doctrine);
        $metadata = $doctrine->getMetadataFactory()->getAllMetadata();
        $doctrine->getProxyFactory()->generateProxyClasses($metadata);

        if ($this->request->args->get('drop-database')) {
            $schema->dropDatabase();

        } else {
            $schema->dropSchema($metadata);
        }

        $schema->createSchema($metadata);

        // >> Add own stuff here
        // <<

        $this->response->showSuccess('Application has been set up.');
    }
}
