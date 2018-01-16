<?php
namespace Frontelus\Library\Response;

class Output
{
    public function __construct(){}
    
    public function strTo_utf8($str)
    { 
        if (mb_detect_encoding($str, 'UTF-8', true) === false)
        { 
            $str = utf8_encode($str); 
        }
        return $str;
    }
    
}
