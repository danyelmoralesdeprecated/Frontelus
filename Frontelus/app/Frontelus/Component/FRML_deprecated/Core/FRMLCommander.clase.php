<?php

namespace Frontelus\Component\FRML\Core;

class FRMLCommander
{

    private $command;
    private $commandPath;
    private $objCache;
    private $stack;
    private $tools;

    public function __construct($insStack, $tools)
    {
        $this->stack = $insStack;
        $this->objCache = array();
        $this->command = array('compose' => 'Command1');
        $this->commandPath = __NAMESPACE__ . '\Commands\\';
        $this->tools = $tools;
    }

    public function execute($command, $args)
    {
        if (!array_key_exists($command, $this->command))
        {
            echo "Command not found...";
            return FALSE;
        }

        $class = $this->getInstanceClass($command);
        $class->run($args);
        return $class->getData();
    }

    private function getInstanceClass($command)
    {
        $auxClass = $this->commandPath . $this->command[$command];
        $class = $this->getCachedClassObj($auxClass, $this->tools);
        return $class;
    }

    private function getCachedClassObj($value, $param)
    {
        if (!array_key_exists(md5($value), $this->objCache))
        {
            $this->objCache[md5($value)] = new $value($param);
        }
        return $this->objCache[md5($value)];
    }

    public function render()
    {
        $info = '';
        $stack = array_filter($this->stack->getStack());
        foreach ($stack as $instruction)
        {
            $info = $this->execute($instruction['type'], $instruction);
        }
        return $info;
    }

}
