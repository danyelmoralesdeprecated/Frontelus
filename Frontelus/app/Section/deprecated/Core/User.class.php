<?php

namespace Model\Core;

use Data\Configs\Config;
use Model\Core\Flag;

class User
{

    private $DBConnector;
    private $id;
    private $flag;
    
    public function __construct($dbConnector, $userID = 0)
    {
        $this->DBConnector = $dbConnector;
        $this->setUserID($userID);
    }

    # perfecto, retorna algún número perteneciente al perfil del usuario
    public function getNumber($type)
    {
        $count = 0;

        switch ($type)
        {
            case 'flag':
                $table = Config::$database['prefix'] . 'myflags';
                $count = $this->DBConnector->count($table, array('myFlags_publisher_id[=]' => $this->id));
                break;

            case 'recommendation':
                $table = Config::$database['prefix'] . 'comments';
                $count = $this->DBConnector->count($table, array('and' => array('comments_recommendation[=]' => 1, 'comments_user_id' => $this->id)));
                break;

            case 'followers':
                # follower_id sigue a user_id, selecciona donde userId sea el seguido
                $table = Config::$database['prefix'] . 'followers';
                $count = $this->DBConnector->count($table, array('followers_user_id[=]' => $this->id));
                break;

            case 'following':
                # follower_id sigue a user_id, selecciona donde userId sea el seguidor
                $table = Config::$database['prefix'] . 'followers';
                $count = $this->DBConnector->count($table, array('followers_follower_id[=]' => $this->id));
                break;
        }

        return (int)$count;
    }
    
    # perfecto, retorna info basica o completa de un usuario
    private function getInfo($mode)
    {
        $table = '';
        
        switch($mode)
        {
            case 'basic':
                $table = Config::$database['prefixView'] . 'user';
                break;
            case 'full':
                $table = Config::$database['prefix'] . 'user';
                break;
        }
        
        return $this->DBConnector->select($table, '*', array('user_id[=]' => $this->id));
    }
    
    public function getSocialNetworks()
    {
        $table = Config::$database['prefix'] . 'social';
        return $this->DBConnector->select($table, array('[><]' . Config::$database['prefix']  . 'socialnetwork'
                                                        =>array('social_network_id'=>'socialnetwork_id')),
                                                                        '*', array($table.'.social_user_id[=]' => $this->id));
    }
    
    public function getPersonalData($private)
    {
        $info = array();

        if ($private)
        {
            $info = $_SESSION['user_flag_logged'][0];
        }
        else
        {
            $info = $this->getInfo('basic');
        }
        return $info;
    }
    
    public function getID()
    {
        return $this->id;
    }
   
    public function setUserID($id)
    {
        if ($id !== NULL)
        {
            $this->id = $id;
            $this->flag = new Flag($this->DBConnector, $id);
        }
    }
    
    public function getFlagObj()
    {
       return $this->flag;
    }
    
}
