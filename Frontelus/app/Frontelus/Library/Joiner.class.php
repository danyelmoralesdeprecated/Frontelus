<?php
namespace Frontelus\Library;
abstract class Joiner
{
    protected static $objContainer = array();
    protected static $primaryObject = NULL;
    
    public function __construct()
    {
        $this->main();
    }
    
    public static function setObj($obj)
    {
        if (!in_array($obj, self::$objContainer))
        {
            self::$objContainer[] = $obj;
        }
    }
    
    public function __call($name, $arguments = array())
    { 
        if (!method_exists(self::$primaryObject, $name))
        {
            foreach(self::$objContainer as $object)
            { 
                if (method_exists($object, $name))
                {
                    self::$primaryObject = $object;
                    break;
                }
            }
        }

        if (method_exists(self::$primaryObject, $name))
        {
            return call_user_func_array(array(self::$primaryObject, $name), $arguments); 
        }
    }
    
    abstract public function main();
}
