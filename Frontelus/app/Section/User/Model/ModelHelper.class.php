<?php

namespace Model;

use \Frontelus\Library\Security\Session;
use Frontelus\Model\FrontelusModel;
use Model\SocialUser;
use Model\User;

class ModelHelper extends FrontelusModel
{

    private $socialUser;
    private $Session;
    private $CurrentUserObj;

    public function __construct()
    {
        $this->socialUser = new SocialUser();     
        $this->Session = new Session();
    }

    public function initialize()
    {
        $this->CurrentUserObj = $this->Session->_use('user_flag_logged')->getSessionObj();
        $this->socialUser->setIdentity($this->CurrentUserObj);
    }
    
    public function follow($followto)
    {
        $this->socialUser->follow(new User(0, $followto));
    }

    public function unfollow($unfollowto)
    {
        $this->socialUser->unfollow(new User(0, $unfollowto));
    }
    
}
