<?php
@session_start(); // global
use Frontelus\FrontelusMain;

class Main extends FrontelusMain
{
    public function __construct($view, $model)
    {
        parent::__construct($view, $model);
    }

    # punto de entrada del framework
    public function main()
    {
      // se checa la configuracion de seguridad del sitio
      $this->securityLoader();
      
      // se cargan los lenguajes
      #$this->languageDetection();
      
      // se cargan dependencias y plugins
      $this->loadDependency();
    }
    
    public function securityLoader()
    {
        if (isset($_SESSION['user_flag_logged']) && !empty($_SESSION['user_flag_logged']))
        {
            header("Location: /user");
            die;
        }
        else
        {
            $this->Router->loadPageFile('yml');
        }
    }
    
    public function loadDependency()
    {
        
        # frontelus mini plugin
        $this->View->load('addPath', 'yml', function($array, $context){
            $urlFriendly = '';
            if (isset($array['friendly']))
            { 
                $urlFriendly = ($array['friendly'])? '/' : '';
                unset($array['friendly']);
            }

            foreach($array as $key => $value)
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