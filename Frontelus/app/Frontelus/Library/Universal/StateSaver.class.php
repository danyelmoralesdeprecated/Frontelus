<?php

namespace Frontelus\Library\Universal;

@session_start();

class StateSaver
{

    CONST SESSION = 0;
    CONST FR_NAME_VAR = 'FR_STATE_SAVER';

    private $container;

    public function __construct()
    {
        $this->container = array();
    }

    public function addValue($name, $value)
    {
        $this->container[$name] = $value;
        return $this;
    }

    public function save($format)
    {
        \Frontelus\R::$SESSION->_use(self::FR_NAME_VAR);
        \Frontelus\R::$SESSION->_destroy();
        switch ($format)
        {
            case self::SESSION:
                \Frontelus\R::$SESSION->save($this->container);
                break;
        }
    }

    public function restore($format, $to)
    {
        switch ($format)
        {
            case self::SESSION:
                $this->parseSession($to);
                break;
        }
    }

    private function parseSession($callback)
    {
        \Frontelus\R::$SESSION->_use(self::FR_NAME_VAR);
        $sessions = \Frontelus\R::$SESSION->getSessionObj();
        foreach ($sessions as $key => $value)
        {
            $callback($key, $value);
        }
    }

    # ---------------------------------

    public function callback_toDefine()
    {
        $callback = function($k, $v)
        {
            if (!defined($k))
            {
                define($k, $v);
            }
        };

        return $callback;
    }

}
