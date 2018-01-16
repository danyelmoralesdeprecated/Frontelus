<?php

namespace Model\Components\User;

use \Model\Components\User\Component;

class Composite extends Component
{

    private $child = array();

    public function __construct($name)
    {
        parent::__construct($name);
    }

    # NOT AVAILABLE

    public function add(Component $c)
    {
        $name = $c->getName();
        $this->child[$name] = $c;
    }

    public function delete(Component $c)
    {
        $name = $c->getName();
        unset($this->child[$name]);
    }

    public function show($deep)
    {
        
    }

    public function getInfo()
    {
        $buffer = array();
        foreach ($this->child as $k => $value)
        {
            $buffer[$k] = $value -> getInfo();
        }
        return $buffer;
    }

}
