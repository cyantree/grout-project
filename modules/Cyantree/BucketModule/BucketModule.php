<?php
namespace Grout\Cyantree\BucketModule;

use Cyantree\Grout\App\GroutFactory;
use Cyantree\Grout\App\Module;
use Cyantree\Grout\Bucket\Bucket;
use Cyantree\Grout\Bucket\DoctrineBucket;
use Cyantree\Grout\Bucket\FileBucket;
use Grout\Cyantree\BucketModule\Types\BucketConfig;

class BucketModule extends Module
{
    const TYPE_FILE = 'file';
    const TYPE_DOCTRINE = 'doctrine';
    const TYPE_GROUT_DATABASE = 'groutDatabase';

    /** @var \Cyantree\Grout\Bucket\Bucket */
    public $bucket;

    public function init()
    {
        if($this->bucket){
            return;
        }

        /** @var BucketConfig $config */
        $config = $this->app->config->get($this->type, $this->id, new BucketConfig());

        if($config->type == self::TYPE_FILE){
            $this->bucket = new FileBucket();
            $this->bucket->directory = $this->app->parseUri($config->config['directory']);
        }elseif($config->type == self::TYPE_DOCTRINE){
            $connection = $config->config['connection'];
            $this->bucket = new DoctrineBucket($connection);
            $this->bucket->table = $config->config['table'];
        }
    }
}