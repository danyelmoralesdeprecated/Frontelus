<?php

namespace Frontelus\Component\FRML\Core\Commands;

abstract class SuperCommand implements ICommand
{

    protected $data;
    protected $tools;
    
    public function __construct($tools)
    {
        $this->data = '';
        $this->tools = $tools;
    }

    public function getData()
    {
        if (!empty($this->data))
        {
            return $this->data;
        }
    }

}
