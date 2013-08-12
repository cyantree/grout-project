<?php
namespace Grout\Cyantree\TranslatorModule;

use Cyantree\Grout\App\Module;
use Grout\Cyantree\TranslatorModule\Types\TranslatorConfig;
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Cache\Storage\Adapter\FilesystemOptions;
use Zend\I18n\Translator\Translator;

class TranslatorModule extends Module
{
    /** @var Translator */
    public $translator;

    /** @var TranslatorConfig */
    public $moduleConfig;

    public function init()
    {
        /** @var TranslatorConfig $config */
        $this->moduleConfig = $config = $this->app->config->get($this->type, $this->id, new TranslatorConfig());

        $translator = new Translator();

        if(!$this->app->config->developmentMode){
            $c = new Filesystem();
            $o = new FilesystemOptions();
            $o->setCacheDir($this->app->parseUri($config->cacheDirectory));
            $c->setOptions($o);
            $translator->setCache($c);
        }

        $translator->setLocale($config->defaultLanguage);

        $folder = $this->app->parseUri($config->translationsDirectory);
        foreach($config->contexts as $context => $file){
            if(is_int($context)){
                $context = $file;
                $file .= '.mo';
            }
            $translator->addTranslationFilePattern('gettext', $folder, '%s/'.$file, $context);
        }

        $this->translator = $translator;
    }
}