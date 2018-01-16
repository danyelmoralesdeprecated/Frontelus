<?php

namespace Model;

use Data\Configs\Config;
use Frontelus\Library\Thirdparty\DB\Medoo\Medoo;
use Frontelus\Model\FrontelusModel;

class BaseModel extends FrontelusModel
{

    protected $DBConnector;
    protected $userView_DB;
    protected $userTable_DB;
    protected $followersTable_DB;
    protected $socialTable_DB;
    protected $socialNetworkTable_DB;
    protected $recommendationTable_DB;
    protected $extensionTable_DB;
    
    public function setDefaultDBConnector()
    {
        $this->userView_DB = Config::$database['prefixView'] . 'user';
        $this->userTable_DB = Config::$database['prefix'] . 'user';
        $this->followersTable_DB = Config::$database['prefix'] . 'followers';
        $this->socialTable_DB = Config::$database['prefix'] . 'social';
        $this->socialNetworkTable_DB = Config::$database['prefix'] . 'socialnetwork';
        $this->recommendationTable_DB = Config::$database['prefix'] . 'comments';
        $this->extensionTable_DB = Config::$database['prefix'] . 'extension';
        
        if (!isset($this->DBConnector) || empty($this->DBConnector))
        {
            $array = array(
                'database_type' => Config::$database['type'],
                'database_name' => Config::$database['database'],
                'server'        => Config::$database['host'],
                'username'      => Config::$database['user'],
                'password'      => Config::$database['password'],
                'charset'       => 'utf8'
            );

            $this->DBConnector = new Medoo($array);
        }
    }

    public function setDBConnector($dbconnector)
    {
        $this->DBConnector = $dbconnector;
    }

}
