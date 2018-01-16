<?php

namespace Model;

use Model\BaseModel;
use Model\User;
use \Frontelus\R;

class UserPassport extends BaseModel
{

    private $email;
    private $password;
    private $user;
    private $saver;
    
    public function __construct()
    {
        parent::__construct();

        $this->user = new User();
        $this->saver = new \Frontelus\Library\Universal\StateSaver();
        $this->setDefaultDBConnector();
    }

    public function validate()
    {
        if (empty($this->email) || empty($this->password))
        {
            return FALSE;
        }

        $user = $this->DBConnector->select($this->userView_DB, array('user_password'), array('user_email[=]' => $this->email));

        if (count($user) != 1)
        {
            return FALSE;
        }

        if ($user[0]['user_password'] !== $this->password)
        {
            return FALSE;
        }

        $dataUser = $this->DBConnector->select($this->userView_DB, '*', array('AND' => array('user_password[=]' => $this->password, 'user_email[=]' => $this->email)));
        if (count($dataUser) != 1)
        {
            return FALSE;
        }

        if (!$this->user->setPersonalInfo($dataUser))
        {
            return FALSE;
        }

        R::$SESSION->_use('user_flag_logged')->save($this->user);
        $this->saver->addValue('CURRENT_USER', $this->user->getUsername())->save(0);
        
        return TRUE;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        # security issues here
        $this->password = $password;
    }

}
