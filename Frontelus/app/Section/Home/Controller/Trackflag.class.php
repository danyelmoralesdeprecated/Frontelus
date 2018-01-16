<?php

namespace Controller;

use \Frontelus\Controller\FrontelusController as FController;
use Model\UserPassport;

class Trackflag extends FController
{

    private $userPassport;

    public function onLoad()
    { 
        $this->userPassport = new UserPassport();
    }

    public function login()
    {

        if (isset($_POST['email']) && isset($_POST['password']))
        {

            $this->userPassport->setEmail($_POST['email']);
            $this->userPassport->setPassword($_POST['password']);

            if ($this->userPassport->validate())
            {
                header("Location: index.php");
                die();
            }
            else
            {
                header("Location: /images/errorDeLogueo.jpg");
            }
        }
    }

}
