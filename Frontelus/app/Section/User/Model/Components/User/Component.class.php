<?php

namespace Model\Components\User;

abstract class Component
{
    protected $name;
    
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    abstract public function add(Component $c);
    abstract public function delete(Component $c);
    abstract public function show($deep);
    abstract public function getInfo();
    
    public function getName()
    {
        return $this->name;
    }
}
