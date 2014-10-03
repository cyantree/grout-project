<?php
namespace Grout\AppModule\WebConsoleCommands\Development;

use Doctrine\ORM\Tools\SchemaTool;
use Grout\AppModule\Types\AppWebConsoleCommand;

class SetupCommand extends AppWebConsoleCommand
{
    public function execute()
    {
        if (!$this->request->args->get('setup')) {
            $this->_showHelp();

        } else {
            $this->_setup();
        }
    }

    private function _showHelp()
    {
        $this->show('Execute with --setup to run setup.');

        $this->show('--drop-database: Drops whole database and recreates all tables. Use with caution!');
    }

    private function _setup()
    {
        $this->show('Application will be set up.');

        $factory = $this->factory();

        $doctrine = $factory->doctrine();

        $this->app->dataStorage->deleteAllStorages();
        $this->app->cacheStorage->deleteAllStorages();

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

        $this->result->showSuccess('Application has been set up.');
    }
}