<?php

namespace Model;

use Model\BaseModel;
use Model\SocialUser;
use \Frontelus\Library\Security\Session;
use Model\Website;

class Extension extends BaseModel
{

    private $user;
    private $website;

    public function __construct()
    {
        $this->socialUser = new SocialUser();
        $this->Session = new Session();
    }

    public function firstTimeInstalled($navigator)
    {
        $firstTime = $this->DBConnector->select($this->extensionTable_DB, 'extension_first_time', array('and' =>
            array('extension_user_id[=]' => $this->user->getID(), 'extension_navigator_id[=]' => $navigator)));
        
        if (count($firstTime) > 0)
        {
            if ($firstTime[0])
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
        
        return -1;
    }

    public function saveWebsite($url, array $info, $flag = NULL, $recommendation = NULL)
    {
        
    }

    public function saveHistory(array $history)
    {
        
    }

    public function saveMarker(array $marker)
    {
        
    }

    public function getFriends($url)
    {
        
    }

    public function initialize()
    {
        $this->user = $this->Session->_use('user_flag_logged')->getSessionObj();
        $this->website = new Website();
        $this->setDefaultDBConnector();
    }

}
