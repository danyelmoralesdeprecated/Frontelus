<?php

namespace Model;

use Model\BaseModel;
use Model\User;

class SocialUser extends BaseModel
{

    private $identitiyUser;

    public function __construct()
    {
        $this->setDefaultDBConnector();
    }

    public function isFollowing(User $followed)
    {
        return $this->DBConnector->count($this->followersTable_DB, array('AND' => array(
                        'followers_user_id[=]'     => $followed->getID(),
                        'followers_follower_id[=]' => $this->identitiyUser->getID()
        )));
    }

    public function follow(User $followed)
    {
        if (!$this->isFollowing($followed))
        {
            $this->DBConnector->insert($this->followersTable_DB, array(
                'followers_user_id'     => $followed->getID(),
                'followers_follower_id' => $this->identitiyUser->getID()
            ));
        }
    }

    public function unfollow(User $followed)
    {
        if ($this->isFollowing($followed))
        {
            $this->DBConnector->delete($this->followersTable_DB, array('AND' => array(
                    'followers_user_id[=]'     => $followed->getID(),
                    'followers_follower_id[=]' => $this->identitiyUser->getID()
            )));
        }
    }

    public function setWebsiteIdentity(Website $website)
    {
        $this->website = $website;
    }

    public function setIdentity(User $user)
    {
        $this->identitiyUser = $user;
    }

    public function getFollower()
    {
        return $this->DBConnector->count($this->followersTable_DB, array('followers_user_id[=]' => $this->identitiyUser->getID()));
    }

    public function getFollowing()
    {
        return $this->DBConnector->count($this->followersTable_DB, array('followers_follower_id[=]' => $this->identitiyUser->getID()));
    }

    public function getFriends()
    {
        
    }

}
