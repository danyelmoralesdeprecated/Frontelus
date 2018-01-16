<?php

namespace Frontelus\Library\FileMapper;

class ChildStorage
{
    private $Storage;
    
    public function __construct()
    {
        $this->Storage = array();
    }
    
    public function addToStorage($key, $value, $type, $index = '')
    {
        if ($index === '')
        {
            $this->Storage[$type][$key][] = $value; 
        }
        else
        {
            $this->Storage[$type][$key][$index] = $value;
        }
    }
    
    public function getStorage()
    {
        return $this->Storage;
    }
    
    public function getStorageKey($key, $type)
    {
        return $this->Storage[$type][$key];
    }
    
    public function isFilled()
    {
        if (count($this->Storage) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function reset()
    {
        $this->Storage = array();
    }
}
