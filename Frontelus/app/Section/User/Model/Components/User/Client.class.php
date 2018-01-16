<?php

namespace Model\Components\User;

use \Model\Components\User\Composite;
use \Model\Components\User\Block;

class Client
{

    private $composite;

    public function __construct($value)
    {
        $this->composite = new Composite('USER');
        $this->composite->add(new Block\UserInfo('user_info', $value));
    }
    
    public function run()
    {
        return $this->composite->getInfo();
    }
    
}
