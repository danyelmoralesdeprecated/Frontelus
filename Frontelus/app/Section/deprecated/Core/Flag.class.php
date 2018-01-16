<?php

namespace Model\Core;

use Data\Configs\Config;

class Flag
{

    private $DBConnector;
    private $tableRegister;
    private $tableContainer;
    private $id;
    
    public function __construct($dbConnector, $userID)
    {
        $this->DBConnector = $dbConnector;
        $this->tableRegister = Config::$database['prefix'] . 'myflags';
        $this->tableContainer = Config::$database['prefixView'] . 'container_medium';
        $this->id = $userID;
    }
    
    public function setID($id)
    {
        $this->id = $id;
    }
    
    public function getFlags($priority = '', $orderBy = '', $max = 30, $min = 0)
    {
        $dataArr = array($this->tableRegister . '.myFlags_publisher_id[=]' => $this->id);
        $dataArr['LIMIT'] =  $max;
        
        # ORDER BY just work for container_medium
        if ($orderBy !== '')
        {
            $dataArr['ORDER'] = $this->tableContainer . $orderBy;
        }

        if ($priority !== '')
        {
            $dataArr['myFlags_priority[=]'] = $priority;
            $dataArr = array('AND' => $dataArr);
        }
        
        $response =  $this->DBConnector->select($this->tableContainer, array('[><]' . $this->tableRegister
               => array('container_id' => 'myFlags_id')), '*', $dataArr);
        
        return $response;
    }

    public function countFlags()
    {
        $data = 0;

        if ($this->id === '*')
        {
            $data = $this->DBConnector->count($this->tableRegister);
        } else
        {
            $data = $this->DBConnector->count($this->tableRegister, array('myFlags_publisher_id[=]' => $this->id));
        }

        return $data;
    }

}
