<?php

namespace Frontelus\Component\FRML\Core;

use Frontelus\Library\Dictionary;

class FRMLStack
{

    private $stack;

    public function __construct()
    {
        $this->stack = new Dictionary();
    }

    public function getStack()
    {
        return $this->stack->getWordBuffer();
    }

    public function pop($index)
    {
        $tempStack = $this->getStack();
        if (array_key_exists($index, $tempStack))
        {
            return $tempStack[$index];
        }
        return FALSE;
    }

    public function pushArr(array $instruction)
    {
        $this->stack->setDefinition_wordBuffer_NI($instruction);
    }

    public function push($instruction)
    {
        $this->stack->setDefinition_word_NI($instruction);
    }

}
