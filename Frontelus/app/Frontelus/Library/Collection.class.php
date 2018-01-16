<?php

namespace Frontelus\Library;

use Frontelus\Library\Thirdparty\Yaml\Spyc;
use Frontelus\R;

final class Collection
{

    private $GlobalPath;
    private static $Buffer;

    public function __construct()
    {
        $this->GlobalPath = R::$APP_PATH . R::$DS;
    }
    
    public function generatePath($file)
    {
        return $this->GlobalPath . $file;
    }

    public function generatePathView($dirLayout, $layout)
    {
        $layoutFileBaseName = R::getSysCfg('ViewDir') .  R::$DS . $dirLayout . R::$DS . $layout;
        $layoutFileAltr = R::getSysCfg('CommonDir') . R::$DS . $layoutFileBaseName;

        if(is_file($this->GlobalPath .  R::$Microsite . $layoutFileBaseName))
        {
            return R::$Microsite . $layoutFileBaseName;
        }
        elseif(is_file($this->GlobalPath . $layoutFileAltr))
        {
            return $layoutFileAltr;
        }
        
        return '';
    }
    
    public function getYmlFileContent($file)
    {
        $path = $this->generatePath($file); 
        return Spyc::YAMLLoad($path);
    }

    public function getYmlContent($string)
    {
        return Spyc::YAMLLoadString($string);
    }

    public function getContent($file)
    {
        $path = $this->generatePath($file);
        return @file_get_contents($path);
    }

    public function getIncludedContent($file)
    {
        $path = $this->generatePath($file); 
        ob_start(array('self', 'setIncludedContentBuffer'));
        require $path;
        ob_end_flush();
        return self::$Buffer;
    }

    private static function setIncludedContentBuffer($data)
    {
        self::$Buffer = $data;
    }
    
    public function getContentByType($file)
    {
        if ($this->getExtension($file) === 'php')
        { 
            return $this->getIncludedContent($file);
        } 
        elseif ($this->getExtension($file) === 'yml')
        {
            return $this->getYmlFileContent($file);
        }
        else
        {
            return $this->getContent($file);
        }
    }

    public function includeFIle($file)
    {
        if ($file !== '')
        {
            $path = $this->generatePath($file);
            require_once $path;
        }
    }
    
    public function setContent($placeHolder, $replace, $where, $file = TRUE)
    {
        if ($file)
        {
            $placeHolder = '%{' . $placeHolder . '}%';
        }

        return str_replace($placeHolder, $replace, $where);
    }

    public function cleanValue($value)
    {
        return ltrim($value, '@');
    }

    public function isFileOrText($value)
    {
        $symbol = substr($value, 0, 1);
        if ($symbol === '@')
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }

    public function getExtension($nombre)
    {
        $extension = FALSE;
        $ext = "";
        // Mientras no exista extensión en el string
        while (!$extension)
        {
            list($ext, $pos) = $this->getExtensionPos($nombre);
            if ($ext)
            {
                // si existe ext, salimos
                $extension = TRUE;
                break;
            }
            // si no hay extensión eliminamos posible punto
            $nombre = $this->cleanExtensionDot($nombre, $pos);
            // si se han consumido todos los caracteres salimos
            if (!$nombre)
            {
                break;
            }
        }
        // por lo tanto si no hay extensión finalizamos
        if (!$extension)
        {
            return FALSE;
        }
        // en otro caso retornamos la extensión
        return strtolower($ext);
    }

    public function getExtensionPos($str)
    {
        $dot = strrpos($str, '.') + 1;
        return array(substr($str, $dot), $dot);
    }

    public function cleanExtensionDot($str, $pos)
    {
        if ($pos <= 0)
        {
            return false;
        }
        return substr($str, 0, $pos - 1);
    }

    public function getStringBetween($str, $from, $to)
    {
        $sub = substr($str, strpos($str, $from) + strlen($from), strlen($str));
        return substr($sub, 0, strpos($sub, $to));
    }

    public function filterFileName($file)
    {
        $regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
        preg_replace($regex, '', $file);
        return $file;
    }

    public function fileExists($file, $callBackFalse = null, $callBackTrue = null)
    {
        $path = $this->generatePath($file);

        if (!file_exists($path))
        {
            if($callBackFalse !== null)
            {
                $callBackFalse();
            }
            return false;
        }
        else
        {
            if($callBackTrue !== null)
            {
                $callBackTrue();
            }
            return true;
        }
    }
    
    public function getReflectionObj($obj)
    {
        $class_info = new ReflectionClass($obj);
        return $class_info;
    }
    
    public function getLocalePath($lang, $domain, $ext)
    {
        $pathToLocale =  R::getSysCfg('OptionalFilesDir') . R::$DS . 'I18N' .  R::$DS . 'Locale'; 
        $localePath = $pathToLocale . R::$DS . 'default' . R::$DS .$domain . '.' . $ext;
        
        if ($lang !== FALSE)
        {
            $folderExt = ($ext === 'po' || $ext === 'mo') ? 'LC_MESSAGES' : 'LC_' . strtoupper($ext) . '_MESSAGES' ;
            $localePath = $pathToLocale  . R::$DS . $lang . R::$DS . $folderExt . R::$DS .$domain . '.' . $ext;
        }
        
        return $localePath;
    }
    
    public function filterGetPage($path, $base = false)
    {
        if ($base){ $path = basename($path); }
        $newPath = preg_replace('/[^A-Za-z0-9]/', '', $path);
        return $newPath;
    }

    public function existAjaxRequest()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {        
               return true;
        }
        
        return false;
    }
}
