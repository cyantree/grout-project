<?php
namespace Grout\AppModule\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

class SampleEntity
{
    public static $_CLASS_ = __CLASS__;

    public $id;
    public $text;

    public static function loadMetadata(ClassMetadata $data)
    {
        $builder = new ClassMetadataBuilder($data);

        $builder->setTable('sample');

        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->createField('text', 'string')->length(64)->build();
    }
}