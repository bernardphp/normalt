<?php

namespace Fixtures;

class Import
{
    protected $id = 1;
    protected $metadata = array(
        'name' => 'RussianUsers',
        'file' => 's3://bucket/users.csv',
    );
}
