<?php
namespace Grout\AppModule\ConsoleCommands\Live;

use Cyantree\Grout\Tools\FileTools;
use Grout\Cyantree\UniversalConsoleModule\Types\UniversalConsoleCommand;

class DeployAssetsCommand extends UniversalConsoleCommand
{
    public function execute()
    {
        if (!$this->request->args->get('deploy')) {
            $this->showHelp();

        } else {
            $this->deploy();
        }
    }

    private function showHelp()
    {
        $this->response->showInfo('Execute with --deploy to deploy assets.');

        if ($this->app->getConfig()->developmentMode) {
            $this->response->showInfo('--clean: Deletes all contents first. Otherwise the assets will be overwritten.');
        }
    }

    private function deploy()
    {
        $definitions = $this->app->getComponentDefinitions();

        if ($this->app->getConfig()->developmentMode) {
            $cleanUp = !!$this->request->args->get('clean');

        } else {
            $cleanUp = false;
        }


        foreach ($definitions as $definition) {
            $sourcePath = $definition->path . 'public/';
            $targetPath = $this->app->getPublicAssetPath($definition->type) . '/';

            if (!is_dir($sourcePath)) {
                continue;
            }

            if ($cleanUp) {
                FileTools::deleteContents($targetPath);
            }

            FileTools::copyDirectory($sourcePath, $targetPath);

            $this->response->showInfo('Deployed ' . $definition->type);
        }

        $this->response->showSuccess('Deployment successful.');
    }
}
