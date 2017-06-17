<?php

namespace Shared\DB2;

class DB2Record
{
    protected $id;
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function getFields() : array
    {
        return $this->fields;
    }

    public function getField($index)
    {
        return $this->fields[$index];
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId() : int
    {
        return $this->id;
    }
}
