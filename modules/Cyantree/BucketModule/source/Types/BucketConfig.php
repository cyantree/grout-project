<?php
namespace Grout\Cyantree\BucketModule\Types;

use Grout\Cyantree\BucketModule\BucketModule;

class BucketConfig
{
    public $type = BucketModule::TYPE_FILE;

    public $config = array(
        'directory' => 'data://buckets/'
    );
}