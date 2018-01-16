<?php

namespace Frontelus\Library;

use \Frontelus\R;

class Autoloader
{
    public static $ignoredList;
    
    public static function parseClass($class)
    {
        $ds = R::$DS;
        $appDirectory = R::$APP_PATH;
        $extFile = R::$AutoLoaderExtFile;
        $clase = str_replace("\\", "$ds", $class);
        $siteDir = R::$Microsite;

        $file = $appDirectory       . $ds   . $clase    . $extFile;
        $fileAlt = $appDirectory    . $ds   . $siteDir  . $ds       . $clase . $extFile;
        $fileAlt2 = $appDirectory   . $ds   . 'Common'  . $ds       . $clase . $extFile;

        self::getFile($file, $fileAlt, $fileAlt2);
    }

    private static function getFile($file, $fileAlt, $fileAlt2)
    {
        if(self::isAutoloadable(basename($file)))
        {
            return;
        }

        if (is_file($file))
        {
            require_once $file;
        } 
        elseif (is_file($fileAlt))
        {
            require_once $fileAlt;
        } 
        elseif (is_file($fileAlt2))
        {
            require_once $fileAlt2;
        } 
        else
        {
            $msgError = 'Something went wrong while parsing the class  '
                        . $file . ' or ' . $fileAlt . ' or ' . $fileAlt2 
                        . '. May be you should ' 
                        . 'use a \\ before the class name: \\foo::bar()';
            die($msgError);
        }
    }
    
    private static function isAutoloadable($file)
    {
        if(in_array($file, self::$ignoredList))
        {
            return  TRUE;
        }
        
        return FALSE;
    }

    public static function setIgnoringList(array $list)
    {
        self::$ignoredList = $list;
    }
    
    public static function addIgnoredClass($class)
    {
        array_push(self::$ignoredList, $class);
    }
}
