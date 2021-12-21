<?php

namespace App;

class Task
{
    public $table_name = 'taskdemo';
    private $id;
    private $type;
    private $name;
    private $description;
    private $notes;
    private $tags;
    private $prio;
    private $status;
    private $duedate;
    private $createdDT;
    private $modifiedDT;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function __destruct()
    {
    }

    public function get($key)
    {
        return $this->$key;
    }

    public function set($key, $value)
    {
        $this->$key = strval($value);
    }
}
