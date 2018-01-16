<?php

namespace Frontelus\Library\Security;

class Session
{

    private $sessionName;
    
    public  function _use($sessionName)
    {
        $this->sessionName = $sessionName;
        return $this;
    }

    public function createFlag(array $flag)
    {
        if (!isset($flag[1]))
        { 
            define($flag[0], 1);
        }
        else
        {
            define($flag[0], $flag[1]);
        }
    }
    
    public function save($value, array $flag = array())
    {
        if (is_object($value) || is_array($value))
        {
            $value = serialize($value);
        }

        $_SESSION[$this->sessionName] = $value;
        
        if (count($flag) >= 1)
        {
            $this->createFlag($flag);
        }
    }

    public function getSessionObj()
    {
        if (isset($_SESSION[$this->sessionName]))
        {
            return unserialize($_SESSION[$this->sessionName]);
        }
    }

    public function getSession()
    {
        if (isset($_SESSION[$this->sessionName]))
        {
            return $_SESSION[$this->sessionName];
        }
    }

    public function _destroy($callback = null)
    {
        if (isset($_SESSION[$this->sessionName]))
        {
            unset($_SESSION[$this->sessionName]);
            if ($callback !== null)
            {
                $callback();
            }
        }
    }

    public function validate()
    {
        if (isset($_SESSION[$this->sessionName]) && !empty($_SESSION[$this->sessionName]))
        {
            return TRUE;
        }

        return FALSE;
    }

}
