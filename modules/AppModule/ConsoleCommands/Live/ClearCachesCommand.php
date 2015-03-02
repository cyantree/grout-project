<?php
namespace Grout\AppModule\ConsoleCommands\Live;

use Grout\AppModule\Types\AppConsoleCommand;

class ClearCachesCommand extends AppConsoleCommand
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

        $this->response->showSuccess('All caches have been recreated.');
    }
}
