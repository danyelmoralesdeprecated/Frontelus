<?php

use Frontelus\FrontelusMain;

class Main extends FrontelusMain
{

    public function __construct($view, $model = NULL)
    {
        parent::__construct($view, $model);
    }

    # punto de entrada del framework

    public function main()
    {
        // se checa la configuracion de seguridad del sitio
        $this->securityLoader();

        // se cargan dependencias y plugins
      #  $this->loadDependency();
    }

    public function securityLoader()
    { 
         $this->Router->loadPageFile('yml', 'API');
    }

    public function loadDependency()
    {

        # frontelus mini plugin
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
