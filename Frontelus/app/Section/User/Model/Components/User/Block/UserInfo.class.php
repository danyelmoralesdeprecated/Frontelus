<?php

namespace Model\Components\User\Block;

use \Model\Components\User\Component;
use \Frontelus\Library\Security\Session;
use Model\SocialUser;
use \Model\User;

class UserInfo extends Component
{

    private $user;
    private $Session;

    public function __construct($name, $user)
    {
        parent::__construct($name);
        $this->user = $user;
        $this->Session = new Session();
        $this->socialUser = new SocialUser(); 
    }

    private function getUserInfo($userObj)
    {
        $this->socialUser->setIdentity($userObj);
        $info = array(
            'user_name'       => $userObj->getName(),
            'user_lastName'   => $userObj->getLastname(),
            'user_image'      => $userObj->getProfileImage(),
            'user_userName'   => $userObj->getUsername(),
            'Followers'       =>  $this->socialUser->getFollower(),
            'Following'       =>  $this->socialUser->getFollowing(),
            'Recommendations' => $userObj->getRecommendationCount(),
            'socialNetwork'   => $userObj->getSocialNetworks()
        );
        return $info;
    }

    public function getInfo()
    {
        $info = array('info' => array());
        $userObj = NULL;

        if (defined('CURRENT_USER'))
        {
            if (CURRENT_USER === $this->user)
            {
                $userObj = $this->Session->_use('user_flag_logged')->getSessionObj();
            }
        }

        if ($userObj === NULL)
        {
            $userObj = new User(0, $this->user);
            if ($userObj->getStatus() === 0)
            {
                die('Usuario no registrado');
            }
        }

        $info['info'] = $this->getUserInfo($userObj);
        return $info;
    }

    # NOT AVAILABLE

    public function add(Component $c)
    {
        
    }

    public function delete(Component $c)
    {
        
    }

    public function show($deep)
    {
        return FALSE;
    }

}
