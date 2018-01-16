<?php

namespace Frontelus\Library;

use Frontelus\Library\Dictionary;
use Frontelus\Library\Security\EntropyGenerator;

class Messenger
{
    private $Messages;
    private $Entropy;
    
    public function __construct()
    {
        $this->Messages = new Dictionary();
        $this->Entropy = new EntropyGenerator();
    }
    
    public function read($_id)
    {
        return $this->Messages->getDefinition_word($_id);
    }
    
    public function write($data)
    {
        $_id = $this->Entropy->createNip(5);
        $this->Messages->setDefinition_word($_id, $data);
        return $_id;
    }
    
    public function destroy()
    {
        $this->Messages->resetDictionary();
    }
}
