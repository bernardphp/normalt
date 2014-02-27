<?php

namespace Fixtures;

class Import
{
    protected $id = 1;
    protected $metadata = array(
        'name' => 'RussianUsers',
        'file' => 's3://bucket/users.csv',
    );

    public function getId()
    {
        return $this->id;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }
}
