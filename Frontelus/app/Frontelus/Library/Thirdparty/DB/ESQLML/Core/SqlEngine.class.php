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
// pendiente agregar nivel de seguridad en inputs antes de pasarlo a PDO

namespace Frontelus\Library\Thirdparty\DB\ESQLML\Core;

use Frontelus\Library\Thirdparty\DB\ESQLML\Lib\Dictionary;
use Frontelus\Library\Thirdparty\DB\ESQLML\Core\SqlConnection;

class SqlEngine
{

    private $statement;
    private $handler;
    private $connection;
    private $statementHandler;
    private $placeHolders;
    private $execType;
    private $fetchMode;
    private $fetchAll;
    private $isTransaction;
    private $PDOREFL;
    private $statementObj;
    private $secureMode;
    private $statementTableType;
    
    const SIMPLE_EXEC = 0;
    const ARR_EXEC = 1;

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public function __construct(array $configuration, $transaction, $prefix = '')
    {
        $this->PDOREFL = new \ReflectionClass('\PDO');
        $this->connection = SqlConnection::getMagicInstace();
        $this->placeHolders = new Dictionary();
        $this->statementTableType = new Dictionary();
        $this->secureMode = true;
        
        if (!is_null($configuration))
        {
            $this->setConfiguration($configuration, $transaction, $prefix);
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

    public function setConfiguration(array $configuration, $transaction, $prefix = '')
    {
        if ($transaction)
        {
            $configuration[SqlConnection::PDOATTR] = array(\PDO::ATTR_PERSISTENT => true);
        }
        $this->isTransaction = $transaction;
        $this->handler = $this->connection->connect($configuration, $prefix);
        $this->secureMode();
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

    public function executeQuery($stmObj)
    {
        try
        {
            if ($this->isTransaction)
            {
                $this->executeTransactionQuery($stmObj);
            } 
            else
            {
                $this->executeNormalQuery($stmObj);
            }
        }
        catch(\PDOException  $e)
        {
            if ($this->secureMode):
            Throw new \Exception('Something went wrong with your SQL QUERY. '
                         . 'You Have an error in your SQL query. ' 
                         . 'Turn off the secure Mode to read PDO Exceptions. '
                         . 'This is not a SQL BUG.');
           else:
               Throw new \Exception($e->getMessage());
           endif;
        }
        
        return $this->statementHandler;
    }

    private function executeTransactionQuery($stmObj)
    {
        try
        {
            $this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->handler->beginTransaction();
            $this->executeNormalQuery($stmObj);
            $this->handler->commit();
        } 
        catch (Exception $e)
        {
            $this->handler->rollBack();
            echo "Failed: " . $e->getMessage();
        }
    }

    private function executeNormalQuery($stmObj)
    {
        $this->statement = $stmObj->getStatement();
        $this->statementObj = $stmObj;
        $sth = $this->handler->prepare($this->statement);
        $symTable = $stmObj->getSymbolInputs();

        if (!$sth)
        {         
            $sth = '';
        } 
        elseif (!$this->prepareExec($sth, $symTable))
        {
            $sth = '';
        }
        
        if (!is_null($this->fetchMode))
        {
            $sth = $this->resolveFetching($sth);
        }

        $this->statementHandler = $sth;
        $this->initialize();
        $this->statementObj->reset();
    }

    private function initialize()
    {
        $this->setFMode('ASSOC', true);
        $this->placeHolders->resetDictionary();
        $this->statementTableType->resetDictionary();
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

    private function prepareExec(&$sth, $symTable)
    {
        $executeState = false;
        $this->preparePlaceholders($sth, $symTable);
 
        switch ($this->execType)
        {
            case self::ARR_EXEC:
                $placeHolder = $this->placeHolders->getWords();
                $executeState = $sth->execute($placeHolder);
                break;

            case self::SIMPLE_EXEC:
            default:
                $executeState = $sth->execute();
                break;
        }
        
        return $executeState;
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

    private function setPlaceHolder(&$sth, $placeHolderName, $value, $type = null)
    {
        if ($this->execType === self::ARR_EXEC)
        {
            $this->placeHolders->setDefinition_word($placeHolderName, $this->VPH($value));
        } 
        else
        { 
            if (defined('\PDO::PARAM_' . strtoupper($type)))
            {
                $paramType = 'PARAM_' . strtoupper($type);
                $typelong = $this->PDOREFL->getConstant($paramType); 
                $sth->bindValue($placeHolderName, $this->VPH($value), $typelong);
            }
        }
    }

    public function VPH($value)
    {
        return $this->statementObj->getValuesOfPlaceHolders($value);
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

    private function preparePlaceholders(&$sth, $symTable)
    {
        $this->execType = self::ARR_EXEC;
        if (count($symTable) !== 0)
        {
            $counter = 0;
            foreach ($symTable as $value)
            {
                $this->setRunTimeParamType($value[1], $value[2]);
                $symTable[$counter][2] = $value[2];
                
                if ($value[2] !== 'STR')
                {
                    $this->execType = self::SIMPLE_EXEC;
                }
                
                $counter++;
            }

            foreach ($symTable as $value)
            {                
                $this->setPlaceHolder($sth, $value[0], $value[1], $value[2]);
            }
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

    private function resolveFetching($sth)
    {
        if (!is_object($sth))
        {
            return 0;
        }
        
        if ($this->fetchAll)
        {
            return $sth->fetchAll($this->fetchMode);
        }
        $sth->setFetchMode($this->fetchMode);
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

    public function getSth()
    {
        if (isset($this->statementHandler))
        {
            return $this->statementHandler;
        }
    }

    private function setRunTimeParamType(&$index, &$value)
    {   
        $index = $this->VPH($index);
        $val = $this->statementTableType->getDefinition_word($index);
        if ($val !== '')
        {
            $value = $val;
        }
    }
    
    public function setFMode($mode = 'ASSOC', $all = False)
    {
        $this->fetchAll = $all;
        if ($mode === NULL)
        {
            $this->fetchMode = null;
        }
        elseif (defined('\PDO::FETCH_' . $mode))
        {
            $this->fetchMode = $this->PDOREFL->getConstant('FETCH_' . $mode);
        }
    }

    public function setParamDataType($index, $type)
    {
        $this->statementTableType->setDefinition_word($index, $type);
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

    private function secureMode()
    {
        $this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->handler->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }
    
    public function turnOnSecureMode()
    {
        $this->secureMode = true;
    }
    
    public function turnOffSecureMode()
    {
        $this->secureMode = false;
    }
   
    public function lastInsertId()
    {
        return $this->handler->lastInsertId();
    }
    
    public function getIntermediateStatement()
    {
        return $this->statement;
    }
    
    public function getEngine()
    {
        return $this;
    }
}
