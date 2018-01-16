<?php

namespace Controller;

use \Frontelus\Controller\FrontelusController as FController;
use \Frontelus\Library\Universal\StateSaver;
use \Model\Components\User as UserComponent;

class UserController extends FController
{

    private $saver;

    public function onLoad()
    {
        $this->saver = new StateSaver();
        $this->saver->restore(0, $this->saver->callback_toDefine());
        $this->Model->initialize();
    }

    public function loadComponents()
    {
        $user = CURRENT_USER;
        if (isset($_GET['user']) && !empty($_GET['user']))
        {
            $user = $_GET['user'];
        }

        $components = new UserComponent\Client($user);
        $package = $components->run();
        $this->View->setMessage($package['user_info']);
    }

    public function follow()
    {
        if (!isset($_GET['user']))
        {
            return FALSE;
        }
        $followto = $_GET['user'];
        if (CURRENT_USER === $followto)
        {
            return FALSE;
        }
        $this->Model->follow($followto);
    }

    public function unfollow()
    {
        if (!isset($_GET['user']))
        {
            return FALSE;
        }
        $unfollowto = $_GET['user'];
        if (CURRENT_USER === $unfollowto)
        {
            return FALSE;
        }
        $this->Model->unfollow($unfollowto);
    }

    public function logout()
    {
        unset($_SESSION['user_flag_logged']);
        header("Location: /login");
        die;
    }

}
