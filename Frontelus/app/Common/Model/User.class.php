<?php

namespace Model;

use Model\BaseModel;
use Model\Website;

class User extends BaseModel
{

    CONST UNKNOWN_USER = 0;
    CONST INVALID_USER = 1;
    CONST KNOWN_USER = 3;

    private $userName;
    private $profileImage;
    private $lastName;
    private $status;
    private $email;
    private $name;
    private $id;
    private $website;
    
    public function __construct($id = 0, $userName = NULL)
    {
        parent::__construct();
        $this->website = new Website();
        $this->status = self::UNKNOWN_USER;
        $this->initialize($id, $userName);
        $this->website->setOwner($this);
    }

    public function initialize($id, $userName = NULL)
    {
        $wcol = 'user_id';
        $value = $id;

        if ($id === 0)
        {
            if ($userName !== NULL)
            {
                $wcol = 'user_userName';
                $value = $userName;
            }
            else
            {
                return FALSE;
            }
        }

        $this->setDefaultDBConnector();
        $user = $this->DBConnector->select($this->userView_DB, '*', array($wcol . '[=]' => $value));
        if (count($user) != 1)
        {
            return FALSE;
        }

        if (!$this->setPersonalInfo($user))
        {
            return FALSE;
        }

        $this->status = self::KNOWN_USER;
        return TRUE;
    }

    public function setPersonalInfo(array $personalInfoArray)
    {
        $personalInfo = $personalInfoArray[0];
        $this->userName = $personalInfo['user_userName'];
        $this->id = $personalInfo['user_id'];
        $this->email = $personalInfo['user_email'];
        $this->name = $personalInfo['user_name'];
        $this->lastName = $personalInfo['user_lastName'];
        $this->profileImage = $personalInfo['user_image'];
        $this->status = self::KNOWN_USER;
        return TRUE;
    }

    public function getSocialNetworks()
    {
        return $this->DBConnector->select($this->socialTable_DB, array('[><]' . $this->socialNetworkTable_DB
                    => array('social_network_id' => 'socialnetwork_id')), '*', array($this->socialTable_DB . '.social_user_id[=]' => $this->id));
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function getProfileImage()
    {
        return $this->profileImage;
    }

    public function getMorePersonalInfo()
    {
        return $this->DBConnector->select($this->userTable_DB, '*', array('user_id[=]' => $this->id));
    }

    public function getUsername()
    {
        return $this->userName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getLastname()
    {
        return $this->lastName;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getRecommendationCount()
    {
        return $this->DBConnector->count($this->recommendationTable_DB, array('and' => array('comments_recommendation[=]' => 1, 'comments_user_id' => $this->id)));
    }

    public function setProfileImage($path)
    {
        $this->DBConnector->update(
                $this->userTable_DB, array(
            'user_image' => $path
                ), array(
            'user_id[=]' => $this->id
                )
        );

        $this->profileImage = $path;
    }

    public function setPassword($password)
    {
        $this->DBConnector->update(
                $this->userTable_DB, array(
            'user_password' => $password
                ), array(
            'user_id[=]' => $this->id
                )
        );
    }

    public function setUsername($username)
    {
        $this->DBConnector->update(
                $this->userTable_DB, array(
            'user_userName' => $username
                ), array(
            'user_id[=]' => $this->id
                )
        );

        $this->userName = $username;
    }

    public function setEmail($email)
    {
        $this->DBConnector->update(
                $this->userTable_DB, array(
            'user_email' => $email
                ), array(
            'user_id[=]' => $this->id
                )
        );

        $this->email = $email;
    }

    public function setLastname($lastName)
    {
        $this->DBConnector->update(
                $this->userTable_DB, array(
            'user_lastName' => $lastName
                ), array(
            'user_id[=]' => $this->id
                )
        );

        $this->lastName = $lastName;
    }

    public function setName($name)
    {
        $this->DBConnector->update(
                $this->userTable_DB, array(
            'user_name' => $name
                ), array(
            'user_id[=]' => $this->id
                )
        );
        $this->name = $name;
    }

    public function resolveUserName($username)
    {
        $id = '';

        return $id;
    }

    public function __wakeup()
    {
        $this->setDefaultDBConnector();
    }

}
