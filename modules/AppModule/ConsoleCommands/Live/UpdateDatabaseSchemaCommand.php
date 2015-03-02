<?php
namespace Grout\AppModule\ConsoleCommands\Live;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\ORM\Tools\SchemaTool;
use Grout\AppModule\Types\AppConsoleCommand;

class UpdateDatabaseSchemaCommand extends AppConsoleCommand
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
                $skipForeignKeyChecks = $this->request->args->get('skip-foreign-key-checks');

                if ($transferredCode == $code) {
                    $d->beginTransaction();
                    if ($skipForeignKeyChecks && $d->getConnection()->getDatabasePlatform() instanceof MySqlPlatform) {
                        $d->getConnection()->exec('SET FOREIGN_KEY_CHECKS=0');
                    }
                    $tool->updateSchema($d->getMetadataFactory()->getAllMetadata());
                    if ($skipForeignKeyChecks && $d->getConnection()->getDatabasePlatform() instanceof MySqlPlatform) {
                        $d->getConnection()->exec('SET FOREIGN_KEY_CHECKS=1');
                    }
                    $d->commit();
                    $this->response->showSuccess('Schema has been updated.');
                    return;

                } else {
                    $this->response->showError('Passed invalid code.');
                }
            }

            $this->response->showWarning('Schema is not up to date. The following queries would be executed:');

            foreach ($queries as $query) {
                $this->response->showInfo($query);
            }

            $this->response->showWarning("Execute with -code={$code} to update the database schema. Additional options: --skip-foreign-key-checks");

        } else {
            $this->response->showSuccess('Schema is up to date.');
        }
    }
}
