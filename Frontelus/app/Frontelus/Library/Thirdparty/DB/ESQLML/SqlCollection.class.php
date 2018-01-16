<?php

/*
 * =============================================================================
 * Author: Daniel V. Morales ( danyelmorales1991@gmail.com )
 * visit me: www.danyelmorales.com
 * =============================================================================
 *                               LICENSE GPL 
 * =============================================================================
  This file is part of ESQLML.

  ESQLML is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  ESQLML is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with ESQLML.  If not, see <http://www.gnu.org/licenses/>.
 * =============================================================================
 */

namespace Frontelus\Library\Thirdparty\DB\ESQLML;

use Frontelus\Library\Thirdparty\DB\ESQLML\Core\SqlEngine;
use Frontelus\Library\Thirdparty\DB\ESQLML\Lib\Statement;
use Frontelus\Library\Thirdparty\DB\ESQLML\Lib\SupportChecker;

class SqlCollection
{

    private $coreEngine;
    private $statementObj;
    private static $isTransaction = false;

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function __construct(array $configuration = array(), $prefix = '')
    {
        SupportChecker::PDOExists();
        if (isset($configuration))
        {
            $this->setConfiguration($configuration, $prefix);
        }
    }

    public function __call($name, $arguments)
    {
        $method = '_' . $name . '_';
        if (method_exists($this, $method))
        {
            return $this->$method($arguments);
        } else
        {
            call_user_func_array($name, $arguments);
        }
    }

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function get($col = '*', $table = '', $condition = '',
                        $next = '', $values = array(), $left = '')
    {
        $this->setTable($table);

        $placeholders = $this->statementObj->getPlaceHolder($values);
        $this->setCondition($condition, $placeholders);
        $nextPH = $this->statementObj->getStringWithPH($next, $placeholders);
        $this->setNext($nextPH);
        $this->setLeft($left);
        $this->setCols($col);
        $this->statementObj->setStatementType('get');
        $sth = $this->coreEngine->executeQuery($this->statementObj);
        return $sth;
    }

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function put($col, $values, $table = '')
    {
        $this->setTable($table);
        $valuesPH = $this->statementObj->getPlaceHolder($values);
        $this->statementObj->setupValueMap($col, $valuesPH);
        $this->statementObj->setStatementType('set');
        $this->coreEngine->setFMode(NULL, false);
        $sth = $this->coreEngine->executeQuery($this->statementObj);
        return $sth;
    }

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function update($col, $table = '', $condition = '', $values = array())
    {
        $this->setTable($table);
        $this->statementObj->mergeSymTI(true);

        $placeholders = $this->statementObj->getPlaceHolder($values);
        $this->statementObj->setupBinaryValues($col, $placeholders);
        $this->setCondition($condition, $placeholders);

        $this->statementObj->setStatementType('update');
        $this->coreEngine->setFMode(NULL, false);
        $sth = $this->coreEngine->executeQuery($this->statementObj);
        return $sth;
    }

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function delete($table = '', $condition = '', $values = array())
    {
        $this->setTable($table);

        $placeholders = $this->statementObj->getPlaceHolder($values);
        $this->setCondition($condition, $placeholders);

        $this->statementObj->setStatementType('delete');
        $this->coreEngine->setFMode(NULL, false);
        $sth = $this->coreEngine->executeQuery($this->statementObj);
        return $sth;
    }

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function statementH()
    {
        return $this->statementObj;
    }

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function setConfiguration(array $configuration, $prefix = '')
    {
        $this->coreEngine = new SqlEngine($configuration, self::$isTransaction, $prefix);
        $this->coreEngine->setFMode('ASSOC', true);
        $this->statementObj = new Statement($configuration, $prefix);
    }

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function setTable($table)
    {
        if ($table != '')
        {
            $this->statementObj->setTable($table);
        }
    }

    /*
     * ************************************************************************
     * @descrip    
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function setCondition($condition, &$values = array())
    {
        if ($condition != '')
        {
            $this->statementObj->setCondition($condition, $values);
        }
    }

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function setNext($next)
    {
        if ($next != '')
        {
            $this->statementObj->setNext($next);
        }
    }

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */
    public function setLeft($left)
    {
        if ($left != '')
        {
            $this->statementObj->setLeft($left);
        }
    }
    
    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function setCols($col)
    {
        if ($col != '')
        {
            $this->statementObj->setColumn($col);
        }
    }

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function setValue($val)
    {
        if ($val != '')
        {
            $this->statementObj->setValue($val);
        }
    }

    /*
     * ************************************************************************
     * @descrip     will be deprecated
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function setColString($col)
    {
        if ($col != '')
        {
            return $this->statementObj->setValueMap($col);
        }
    }

    /*
     * ************************************************************************
     * @descrip                default value ASSOC	
     * @mode        string     accepted values ASSOC | BOTH | NAMED | NUM | dh 
     * @return      string
     * ***********************************************************************
     */

    public function setFetchMode($mode = 'ASSOC', $all = False)
    {
        $this->coreEngine->setFMode($mode, $all);
    }

    /*
     * ************************************************************************
     * @descrip                default value ASSOC	
     * @mode        string     accepted values ASSOC | BOTH | NAMED | NUM | dh 
     * @return      string
     * ***********************************************************************
     */

    public static function isTransaction($value)
    {
        self::$isTransaction = $value;
    }

    public function getStatement()
    {
        return $this->statementObj;
    }

    public function turnOnSecureMode()
    {
        $this->coreEngine->turnOnSecureMode();
    }
    
    public function turnOffSecureMode()
    {
        $this->coreEngine->turnOffSecureMode();
    }
    
    public function lastInsertId()
    {
        return $this->coreEngine->lastInsertId();
    }
    
    public function getEngine()
    {
        return $this->coreEngine->getEngine();
    }
    
    public function _($index, $type)
    {
        $this->coreEngine->setParamDataType($index, $type);
    }
}
