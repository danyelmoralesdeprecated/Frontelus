<?php

namespace Controller;

use \Frontelus\Controller\FrontelusController as FController;

class Trackflag extends FController
{
    public function loginToAccount()
    {

        if (isset($_POST['email']) && isset($_POST['password']))
        {
            $email = $_POST['email'];
            $password = $_POST['password']; 
            if ($this->Model->CheckLogin($email,$password))
            {
                header("Location: index.php"); die();
            }
            else
            {
                header("Location: /images/errorDeLogueo.jpg");
            }
        }
    }

    public function onLoad()
    {
        
    }

}
