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
namespace Frontelus\Library\Thirdparty\DB\ESQLML\Lib;

use Frontelus\Library\Thirdparty\DB\ESQLML\Lib\Dictionary;
use Frontelus\Library\Thirdparty\DB\ESQLML\Core\PlaceholderHandler;

class Statement
{
    CONST TABLE = 'table';
    CONST TABLE_DEFAULT = 'table_default';
    CONST COND = 'cond';
    CONST NEXT = 'next';
    CONST COL = 'col';
    CONST VAL = 'vals';
    CONST LEFT = 'left';
    
     # statement attr
    private $table;
    private $column;
    private $next;
    private $condition;
    private $values;
    private $left;
    
    # template attr
    private $statementType;
    private $publicTemplate;
    private $container;
    
    # config attr
    private $tableConfig;
    private $prefix;
    
    # objects
    private $placeholderHandler;
    private $symTableInputs;
    private $mergeSymTI;
    private $valuePlaceHoldered;
    private $placeHolders;
    
    public function __construct(array $config, $prefix = '')
    {
        $this->publicTemplate = new Dictionary();
        $this->container = new Dictionary();
        $this->placeholderHandler =  new PlaceholderHandler();
         
        $this->tableConfig = $config;
        $this->prefix = $prefix;
        
        $this->initialize();
        $this->preload();
    }
    
    /*
     * ************************************************************************
     * @descrip     Initialize the system template strings
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */ 
    public function initialize()
    {
         $this->publicTemplate->setDefinition_word(
                 'get', 'SELECT <%col%> FROM <%table%> <%left%> <%cond%> <%next%>');
         $this->publicTemplate->setDefinition_word(
                 'set', 'INSERT INTO <%table%>(<%col%>) VALUES (<%vals%>)');
         $this->publicTemplate->setDefinition_word(
                 'update', 'UPDATE <%table%> SET <%col%> <%cond%>');
         $this->publicTemplate->setDefinition_word(
                 'delete', 'DELETE FROM <%table%> <%cond%>'); 
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
    public function preload()
    {
        $tableDefault = $this->prefix . self::TABLE_DEFAULT;
        $tableDefaultValue = (!isset($this->tableConfig[$tableDefault]))? '' : $this->tableConfig[$tableDefault];
        $this->statementType = null;
        $this->mergeSymTI = false;
        $this->placeHolders = array();

        $this->table = $this->prefix . self::TABLE;
        $this->next = $this->prefix . self::NEXT;
        $this->condition = $this->prefix . self::COND;
        $this->column = $this->prefix . self::COL;
        $this->values = $this->prefix . self::VAL;
        $this->left = $this->prefix . self::LEFT;
        
        $this->container->setDefinition_word($this->table, $tableDefaultValue);
        $this->container->setDefinition_word($this->next, '');
        $this->container->setDefinition_word($this->condition, '');
        $this->container->setDefinition_word($this->column, '');
        $this->container->setDefinition_word($this->values, '');
        $this->container->setDefinition_word($this->left, '');
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
    public function setupValueMap(array $col, array $value)
    {
        $columns = implode(',', $col);
        $values = implode(',', $value);
        $this->setColumn($columns);
        $this->setValue($values);
    }
    
    public function setupBinaryValues(array $cols, array &$value)
    {
        $data = '';
        foreach($cols as $col) 
        {
           $poped = array_shift($value);
           $data .= $col . '=' . $poped . ',';
        }
        $this->setColumn(rtrim($data, ','));
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
    private function isAllowed()
    {
        $result = false;
        $allowed_for_excluding_cols = array('delete');
        if(in_array($this->statementType, $allowed_for_excluding_cols)){ $result = true;}
        return $result;
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
        if($table !== '')
        {
            $this->container->setDefinition_word($this->table, $table);   
        }
    }
    
    public function setColumn($col)
    {
        if($col !== '')
        {
            $this->container->setDefinition_word($this->column, $col);
        } 
    }
    
    public function setNext($next)
    {
        if($next !== '')
        {
            $this->container->setDefinition_word($this->next, $next);
        }
    }
    
    public function setLeft($left)
    {
        if ($left !== '')
        {
            $this->container->setDefinition_word($this->left, $left);
        }
    }
    
    public function setCondition($cond, $values = '')
    {
        if($cond === ''){return;}
        
        $condValue = 'WHERE ' . $cond;
        
        if ($values)
        {
            $condValue = $this->getStringWithPH($condValue, $values);
        }
        $this->container->setDefinition_word($this->condition, $condValue);
    }

    public function setValue($str)
    {
        if($str !== '')
        {
            $this->container->setDefinition_word($this->values, $str);
        }
    }
    
    public function setStatementType($sType)
    {
        $this->statementType = $sType;
    }
    
    private function setSymTableInput($symTI)
    {
        if(count($symTI) !== 0)
        {  
            if($this->mergeSymTI)
            {
                $this->symTableInputs = array_merge($this->symTableInputs, $symTI);
            }
            else
            {
                $this->symTableInputs = $symTI;
            }
        }
    }
    
    public function getStatement()
    {   
        $data = $this->publicTemplate->getDefinition_word($this->statementType); 
        if($data === '' || count($data) === 0) { return '';}
        
        $args = $this->container->getWords();
        $isAllowed = $this->isAllowed();
        if($args === '' || count($args) === 0) { return '';}
        if($args[$this->table] === '' || ($args[$this->column] === '' && !$isAllowed)) { return '';}

        foreach ($args as $key => $value)
        {
            $data = str_replace('<%' . $key . '%>', $value, $data);
        } 

        $this->preload();
        return $data;
    }
    
    public function getValuesOfPlaceHolders($index = '')
    {
        if ($index === '')
        {
            return $this->valuePlaceHoldered;
        }
        
        if (array_key_exists($index, $this->valuePlaceHoldered))
        {
            return $this->valuePlaceHoldered[$index];
        }
    }
    
    public function getSymbolInputs()
    {
        return $this->symTableInputs;
    }
    
    public function getPlaceHolder(array $values)
    {
        $this->valuePlaceHoldered = $values;
        $this->placeholderHandler->parseCondition($values);
        $this->setSymTableInput($this->placeholderHandler->getSymbolTable_inputs());
        return $this->placeholderHandler->getPlaceholder_inputs(); 
    }
    
    public function getStringWithPH($condValue, array $placeholders)
    {
        $data = $condValue;
        foreach($placeholders as $value)
        {
            $data = preg_replace('/@@/', $value, $data, 1);
        }
        return $data;
    }
    
    public function mergeSymTI($value)
    {
        $this->symTableInputs = array();
        $this->mergeSymTI = $value;
    }
    
    public function reset()
    {
        $this->symTableInputs = array();
    }
}
