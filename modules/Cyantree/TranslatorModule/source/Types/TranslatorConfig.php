<?php
namespace Grout\Cyantree\TranslatorModule\Types;

class TranslatorConfig
{
    public $defaultLanguage = 'en';
    public $contexts = array();
    public $cacheDirectory = 'data://zend-cache/';
    public $translationsDirectory = 'data://locale/';
}