<?php

@session_start();

use Frontelus\FrontelusMain;
use \Frontelus\R;

class Main extends FrontelusMain
{

    public function __construct($view, $model)
    {
        parent::__construct($view, $model);
    }

    public function main()
    {
        $this->securityLoader();
        $this->loadDependency();
    }

    public function securityLoader()
    {
        if (!R::$SESSION->_use('user_flag_logged')->validate())
        {
            header("Location: /login");
            die;
        }
        else
        {
            $this->Router->loadPageFile('yml', 'User');
        }
    }

    public function loadDependency()
    {
        $this->View->load('addPath', 'yml', function($array, $context)
        {
            $urlFriendly = '';
            if (isset($array['friendly']))
            {
                $urlFriendly = ($array['friendly']) ? '/' : '';
                unset($array['friendly']);
            }

            foreach ($array as $key => $value)
            {
                $keyT = '$path.' . $key;
                $context->addCfg($keyT, $urlFriendly . $value . '/');
            }
        });
    }

    public function languageDetection()
    {
        $langSys = "YamlLocale";
        $lang = "en_US";
        $this->setLang($langSys, $lang)->setLangDependency(array('test'));
    }

}
