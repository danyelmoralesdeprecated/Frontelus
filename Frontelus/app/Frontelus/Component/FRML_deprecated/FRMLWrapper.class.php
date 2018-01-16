<?php

namespace Frontelus\Component\FRML;

use Frontelus\Component\FRML\Core\FRMLInterpreter;

class FRMLWrapper
{

    private $Interpreter;

    public function __construct()
    {
        $this->Interpreter = new FRMLInterpreter();
    }

    public function bake($data)
    {
        $this->Interpreter->bake($data);
    }

    public function load($file)
    {
        return $this->Interpreter->load($file);
    }

    public function isFRMLFile($file)
    {
        return $this->Interpreter->isFRMLFile($file);
    }

    public function getInfo()
    {
        return $this->Interpreter->getInfo();
    }

}
