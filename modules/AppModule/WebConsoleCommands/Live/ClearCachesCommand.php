<?php
namespace Grout\AppModule\WebConsoleCommands\Live;

use Grout\AppModule\Types\AppWebConsoleCommand;

class ClearCachesCommand extends AppWebConsoleCommand
{
    public function execute()
    {
        $factory = $this->factory();
        $doctrine = $factory->doctrine();

        $this->app->cacheStorage->deleteAllStorages();

        $this->app->dataStorage->clearStorage('CyantreeDoctrineModule/Proxies');
        $doctrine->getProxyFactory()->generateProxyClasses($doctrine->getMetadataFactory()->getAllMetadata());

        // >> Add own stuff here
        // <<

        $this->result->showSuccess('All caches have been recreated.');
    }
}
