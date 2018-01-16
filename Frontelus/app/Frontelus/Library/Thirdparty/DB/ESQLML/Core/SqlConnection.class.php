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
namespace Frontelus\Library\Thirdparty\DB\ESQLML\Core;

class SqlConnection
{
    # configuration key Names
    CONST HOST = 'host';
    CONST USER = 'user';
    CONST PASSWORD = 'password';
    CONST DB = 'database';
    CONST DBMS = 'dbms';
    CONST LANG = 'lang';
    CONST SUPER_PERMISSION = 'useSuper';
    CONST PORT = 'port';
    CONST PDOATTR = 'pAttr';
    
    # attributes
    private $host;
    private $user;
    private $password;
    private $dataBase;
    private $dbms;
    private $port;
    private $connection;
    private $language;
    private $pdoAttr;
    
    # attribute helper
    private static $instance;

    private function __construct(){}

    /*
     * ************************************************************************
     * @descrip     
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */

    public static function getMagicInstace()
    {
        if (!isset(self::$instance))
        {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public static function getInstance()
    {
        if (!(isset(self::$instance) || self::$instance instanceof self))
        {
            self::$instance = new self;
        }
        return self::$instance;
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
    public function connect(array $configuration, $prefix = '')
    {
        if (!isset($this->connection))
        {
            $this->initialize_confUser($configuration, $prefix);
            $this->initialize_confSys($configuration, $prefix);
            $this->createDriverConnection();
            unset($configuration);
        }
        return $this->connection;
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
    public function reconnect(array $configuration, $prefix = '')
    {
        $this->disconnect();
        $this->connect($configuration, $prefix);
        unset($configuration);
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
    public function disconnect()
    {
         $this->connection = null;
         $this->user = null;
         $this->password = null;
         $this->host = null;
         $this->dataBase = null;
         $this->dbms = null;
         $this->port = null;
         $this->language = null;
         $this->pdoAttr = null;
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
    
    private function initialize_confUser(array &$configuration, $prefix = '')
    {
        $host = $prefix . self::HOST;
        $user = $prefix . self::USER;
        $password = $prefix . self::PASSWORD;
        $db = $prefix . self::DB;
 
        if ( !(key_exists($host, $configuration)
            && key_exists($user, $configuration)
            && key_exists($password, $configuration)) )
        {
            throw new Exception('Configuration data(host | user | password) not found!');
        }
        
        $this->dataBase = (key_exists($db, $configuration)) ? $configuration[$db] : null;
        $this->host = $configuration[$host];
        $this->user = $configuration[$user];
        $this->password = $configuration[$password];
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
    
    private function initialize_confSys(array &$configuration, $prefix = '')
    {
        $dbms = $prefix . self::DBMS;
        $pdoAttr = $prefix . self::PDOATTR;
        $port = $prefix . self::PORT;
        $permission = $prefix . self::SUPER_PERMISSION;
        
        $this->pdoAttr = (key_exists($pdoAttr, $configuration)) ? $configuration[$pdoAttr] : array();
        $this->dbms = (key_exists($dbms, $configuration)) ? $configuration[$dbms] : 'mysql';
        $this->port = (key_exists($port, $configuration)) ? $configuration[$port] : '';
        $permissionValue = (key_exists($permission, $configuration)) ? $configuration[$permission] : false;
       
        $this->language = (is_null($this->dataBase)) ? 'DDL' : 'DML' ;
        
        if (!$permissionValue && $this->language === 'DDL')
        {
            throw new Exception('Error! - where is the database name?');
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
    private function createDriverConnection()
    {
        $connectionStr = $this->createConnectionString($this->dbms);
        
        try 
        {
            if ($this->dbms === 'sqlite')
            {
                $this->connection = new \PDO("$connectionStr");
            }
            else
            {
                $this->connection = new \PDO("$connectionStr", "$this->user", "$this->password", $this->pdoAttr);
            }
        }
        catch (PDOException $e)
        {
            echo '[Diver connection] - Error! :' . $e->getMessage() . '<br/>';
            die();
        }
    }
    
    
    /*
     * ************************************************************************
     * @descrip     
     * @note        this method needs to be tested
     * @param       $col		
     * @param       $cond		
     * @param       $table   
     * @return      string
     * ***********************************************************************
     */
    private function createConnectionString($dbms)
    {
        $str = '';
        $portStr = ';port=' . $this->port;
       
        switch($dbms)
        {
            case 'mysql':
                $str = 'mysql:host=' . $this->host;
                break;
            
            case 'sqlite':
                $str = 'sqlite:' . $this->host;
                break;
            
            case 'postgresql':
                $str = 'pgsql:host=' . $this->host;
                break;
            
            case 'oracle':
                //$str = 'oci:';
            default:
                 throw new Exception('Not valid DBMS!');
        }      
        
        $str .= ($this->port === '' || $dbms === 'sqlite') ? '' : $str . $portStr;
        $str .= ($this->language === 'DML' && $dbms !== 'sqlite') ? ";dbname=$this->dataBase" : '';
        
        return $str;
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
    public function getConnection()
    {
        if (isset($this->connection))
        {
            return $this->connection;
        }
        return null;
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

    public function __clone()
    {
        trigger_error("this candy cannot be clonned", E_USER_ERROR);
    }

    public function __wakeup()
    {
        trigger_error("You cannot create another object of this candy", E_USER_ERROR);
    }

}
