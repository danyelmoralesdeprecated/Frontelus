<?php

namespace Frontelus\Library;

class CacheClass
{
    private $objCache;
    
    public function __construct()
    {
        $this->objCache = array();
    }

    public function getInstanceClass($name, $class)
    {
        $class = $this->getCachedClassObj($name, $class);
        return $class;
    }

    private function getCachedClassObj($name, $class)
    {
        if (!array_key_exists(md5($name), $this->objCache))
        {
            $this->objCache[md5($name)] = new $class();
        }
        return $this->objCache[md5($name)];
    }

}
