<?php

namespace Fixtures;

class ImportWrapper
{
    protected $import;

    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    public function getImport()
    {
        return $this->import;
    }
}
