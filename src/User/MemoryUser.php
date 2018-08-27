<?php
namespace OAF\User;

class MemoryUser implements UserInterface
{
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id()
    {
        return $this->id;
    }

    public function username()
    {
        return $this->name;
    }
}