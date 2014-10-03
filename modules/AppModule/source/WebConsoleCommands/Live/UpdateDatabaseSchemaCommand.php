<?php
namespace Grout\AppModule\WebConsoleCommands\Live;

use Doctrine\ORM\Tools\SchemaTool;
use Grout\AppModule\Types\AppWebConsoleCommand;

class UpdateDatabaseSchemaCommand extends AppWebConsoleCommand
{
    public function execute()
    {
        $d = $this->factory()->doctrine();

        $this->app->dataStorage->clearStorage('CyantreeDoctrineModule/Proxies');
        $this->app->cacheStorage->clearStorage('CyantreeDoctrineModule');

        $tool = new SchemaTool($d);

        $queries = $tool->getUpdateSchemaSql($d->getMetadataFactory()->getAllMetadata());

        if ($queries) {
            $hash = '';
            foreach ($queries as $query) {
                $hash = md5($hash . $query);
            }

            $code = substr($hash, 0, 8);

            if ($transferredCode = $this->request->args->get('code')) {
                if ($transferredCode == $code) {
                    $tool->updateSchema($d->getMetadataFactory()->getAllMetadata());
                    $this->result->showSuccess('Schema has been updated.');
                    return;

                } else {
                    $this->result->showError('Passed invalid code.');
                }
            }

            $this->result->showWarning('Schema is not up to date. The following queries would be executed:');

            foreach ($queries as $query) {
                $this->result->showInfo($query);
            }

            $this->result->showWarning("Execute with -code={$code} to update the database schema.");

        } else {
            $this->result->showSuccess('Schema is up to date.');
        }
    }
}