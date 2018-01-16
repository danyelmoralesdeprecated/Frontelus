<?php

namespace Frontelus\Library;

class Factory
{

    public static function build_I18N($sys)
    {
        $I18NClass = 'Frontelus\\Library\\I18N\\' . $sys . '\\' . $sys;
        
        if (class_exists($I18NClass))
        {
            return new $I18NClass();
        } 
        else
        {
            throw new \Exception("Invalid I18N type given.");
        }
    }

}
