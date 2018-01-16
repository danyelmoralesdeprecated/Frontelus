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
use Frontelus\Library\Thirdparty\DB\ESQLML\SqlCollection;

class Facade
{
    private $SqlCollection;
    private $columns;
    private $table;
    private $condition;
    private $next;
    private $queryType;
    private $value;
    private $bufChangedCol;
    private $left;
    private $clearVars;
    
    public function __construct(array $configuration = array(), $prefix = '')
    {
        $this->setCfg($configuration, $prefix);
    }
    
    public function setCfg(array $configuration = array(), $prefix = '')
    {
        $this->SqlCollection = new SqlCollection($configuration, $prefix);
        $this->initialize();
    }
    
    private function initialize()
    {
        $this->columns = '';
        $this->condition = '';
        $this->table = '';
        $this->next = '';
        $this->queryType = '';
        $this->left = '';
        $this->value = array();
        $this->bufChangedCol = array();
        $this->clearVars = true;
    }
    
    public function select($col = '*')
    {
        $this->columns = $col;
        $this->queryType = 'select';
        return $this;
    }
    
    public function delete()
    {
        $this->queryType = 'delete';
        return $this;
    }
    
    public function _insert_($col)
    {
        $this->columns = $col;
        $this->queryType = 'insert';
        return $this;
    }
    
    public function _update_($col)
    {
       $this->columns = $col;
       $this->queryType = 'update';
       return $this;
    }
    
    public function from($table = '')
    {
        $this->table = $table;
        return $this;
    }
    
    public function _with_($value)
    {
        $this->value = $value;
        return $this;
    }
    
    public function left($value)
    {
        $this->left = $value;
        return $this;
    }
    
    public function where($condition)
    {
        $this->condition = $condition;
        return $this;
    }
    
    public function next($next)
    {
       $this->next = $next;
       return $this;
    }
    
    # ----------------------------------------------
    public function into($table = '')
    {
        $this->table = $table;
        return $this;
    }
    
    # ----------------------------------------------
    public function __call($name, $arguments)
    {
        if(is_array($arguments[0]))
        {
            $arguments = $arguments[0];
        }
        
        switch($name)
        {
            case 'with':
                $this->_with_($arguments);
                break;
            case 'insert':
                $this->_insert_($arguments);
                break;
            case 'update':
                $this->_update_($arguments);
                break;
        }
        
        return $this;
    }
    
    # ----------------------------------------------
    public function fetchMode($fetch,  $all = False)
    {
        $this->SqlCollection->setFetchMode($fetch, $all);
        return $this;
    }
    
    public function activeTransaction()
    {
        SqlCollection::isTransaction(true);
        return $this;
    }
    
    public function turnOffTransaction()
    {
        SqlCollection::isTransaction(false);
        return $this;
    }
    
    public function go()
    {
        $sth = null;
        $this->process__();
        switch($this->queryType)
        {
            case 'select':
                $sth = $this->SqlCollection->get($this->columns, $this->table, $this->condition, 
                                                 $this->next, $this->value, $this->left);
                break;
            
            case 'update':
                $sth = $this->SqlCollection->update($this->columns, $this->table,
                                             $this->condition, $this->value);              
                break;
            
            case 'delete':
                $sth = $this->SqlCollection->delete($this->table, $this->condition, $this->value);
                break;
            
            case 'insert':
                $sth = $this->SqlCollection->put($this->columns, $this->value, $this->table);
                break;
        }
        
        if ($this->clearVars){$this->initialize();}
        return $sth;
    }
    
    public function turnOnSecureMode()
    {
        $this->SqlCollection->turnOnSecureMode();
    }
    
    public function turnOffSecureMode()
    {
        $this->SqlCollection->turnOffSecureMode();
    }
    
    public function lastInsertId()
    {
        return $this->SqlCollection->lastInsertId();
    }
    
    public function getEngine()
    {
        return $this->SqlCollection->getEngine();
    }
    
    public function turnOffClearVars()
    {
        $this->clearVars = false; 
    }
    
    public function clearVars()
    {
        $this->initialize();
    }
    
    public function _($index, $type)
    {
        $this->SqlCollection->_($index, $type);
        return $this;
    }
    
    public function __($type, $nCols = '*')
    {
        $this->bufChangedCol[$type] = $nCols;
        return $this;       
    }
    
    private function process__()
    {
        if (count($this->bufChangedCol) > 0 )
        {
            $type = key($this->bufChangedCol);
            $l = ($this->bufChangedCol[$type] === '*')?count($this->value):$this->bufChangedCol[$type]; 
            for($i = 0; $i < $l; $i++)
            {
                $this->_($this->value[$i], $type);
            }
        }
    }
}
