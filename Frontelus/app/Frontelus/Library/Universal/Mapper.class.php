<?php
/*                              LICENSE GPL 
 * =============================================================================
    This file is part of Frontelus.

    Frontelus is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Frontelus is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Frontelus.  If not, see <http://www.gnu.org/licenses/>.
 * =============================================================================
 */

# FRONTELUS FILE MAPPER
# --------------------------------------
namespace Frontelus\Library\Universal;
use Controlador\Frontelus\FileMapper\MixinFrontelusFileMapper;
use Controlador\Frontelus\FileMapper\FrontelusFileMapper;

final class Mapper
{
    private static $GeneralHelyKey;
    private static $Mapper;
    private static $Mixin;
    private static $Control;
    
    private function __construct(){}
    
    public static function init($frontalClass)
    {
        self::$Control = $frontalClass;
        self::$GeneralHelyKey = "vjsT00g4cjakc4f5";
        self::$Mixin = new MixinFrontelusFileMapper();
        self::$Mapper = new FrontelusFileMapper();
    }
    
    public static function getMap($file, $public = true) 
    {
        $file = self::$Mixin->filtrarFileName($file);
        $key =  self::$Mixin->createNip(7);
        $cifrado1 = self::$Mixin->cifrarRijndael($file, $key) . "@$key";
        $cifrado2 = self::$Mixin->cifrarRijndael($cifrado1, self::$GeneralHelyKey);
        $fileNameDef = md5($cifrado2);

        if(!self::$Mapper->cargarMapa("layoutMapper"))
        {
            echo "Hubo un error con la carga del mapeo";
            return;
        }

        self::$Mapper->agregarCoordenada($fileNameDef, $cifrado2, $public);
        return $fileNameDef;
    }
  
    public static function getName($hash)
    {
        if(!$hash)
        {
            return FALSE;
        }

        $hash2 = self::$Mixin->descifrarRijndael($hash, self::$GeneralHelyKey);
        $tmp = explode("@", $hash2);
        $file = self::$Mixin->descifrarRijndael($tmp[0], $tmp[1]);
        $textoClaro = self::$Mixin->filtrarFileName($file);
        return $textoClaro;
    }
    
    public static function read($hash, $public = NULL)
    {
        if(!self::$Mapper->cargarMapa("layoutMapper"))
        {
            return FALSE;
        }
        
        if(!$arr = self::$Mapper->cargarCoordenada("map", $hash))
        {
            return FALSE;
        }

        if(!$file = self::getName($arr['content']))
        {
            return FALSE;
        }

        #$public = ($public)? $arr['public'] : "";
        return array($file, $public);
    }
    
    public static function load($hash, $public = FALSE)
    {
        if(! list($file, $path) = self::read($hash, $public))
        {
            return FALSE;
        }
        
        $filename = self::$Control->get__DIR__()  
                  . self::$Control->get_PathLayout() 
                  . "$path$file";
        
        if(!file_exists($filename))
        {
            return FALSE;
        }
        
        if(!$source = file_get_contents($filename))
        {
            return FALSE;
        }
        
        return $source;
    }
    
    public static function simpleLoad($file, $public)
    {
        #$path = ($path)? $path . "/" : "";

        $filename = self::$Control->get__DIR__()  
                  . self::$Control->get_PathLayout() 
                  . "$file";
        
        if(!file_exists($filename))
        {
            return FALSE;
        }
        
        $source = file_get_contents($filename);
        return $source;
    }
    
    public static function sayHello()
    {
        echo "Hello world";
        exit;
    }
}