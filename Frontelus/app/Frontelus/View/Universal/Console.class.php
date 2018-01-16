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

namespace Frontelus\View\Universal;

final class Console
{

    private static $Output;
    private static $Dependency;

    private function __construct()
    {
        
    }

    public static function main($frontalClass)
    {
        self::$Dependency = $frontalClass;
    }

    /**
     * =========================================================================
     *                      STREAM OUTPUT GROUP
     * =========================================================================
     * @desc write content in base layout by replacing a place holder.
     * examples include Console.writeX('helloWorldPh', 'Some Stuff');
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required FacadeView memory location
     * @param string $index Place holder to be replaced
     * @param string $param Content used to replace the place holder
     * @return void 
     */
    public static function write($index, $param)
    {
        return self::$Dependency->addContent($index, $param);
    }

    public static function writeCfg($index, $param)
    {
        return self::$Dependency->addCfg($index, $param);
    }

    public static function writeFile($index, $param)
    {
        return self::$Dependency->addFile($index, $param);
    }

    public static function writeContentFile($index, $param)
    {
        return self::$Dependency->addFileContent($index, $param);
    }
    
    public static function writeRegisteredFile($index, $param, $xml = '')
    {
        return self::$Dependency->addRegisterdFile($index, $param, $xml);
    }
    
    public static function writeReference($index, $param)
    {
        return self::$Dependency->addReference($index, $param);
    }

    public static function writeNewReference($index, $param)
    {
        
    }

    public static function writeFrmlFile($index, $param)
    {
        
    }

    public static function writeFrmlStr($index, $param)
    {
        
    }

    /**
     * =========================================================================
     *                      SETTERS GROUP
     * =========================================================================
     * @desc sets values neede in $Dependency class.
     * examples include Console.setX('some stuff');
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required FacadeView memory location
     * @param string $value value to set up
     * @return void 
     */
    public static function setLayout($value)
    {
        self::$Dependency->setDefaultLayout($value);
    }
    
    /**
     * =========================================================================
     *                      ACTION GROUP
     * =========================================================================
     * @desc write content in base layout by replacing a place holder.
     * examples include Console.writeX('helloWorldPh', 'Some Stuff');
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required FacadeView memory location
     * @param String $index Place holder to be replaced
     * @param string $param Content used to replace the place holder
     * @return void 
     */
    public static function printView()
    {
        
    }

}
