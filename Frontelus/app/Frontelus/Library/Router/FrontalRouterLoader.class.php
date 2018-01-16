<?php

namespace Frontelus\Library\Router;

use Frontelus\Config\ArrayLoader;
use Frontelus\R;

class FrontalRouterLoader
{
    private $Router;
    private $RouterConfiguration;
    
    public function __construct($router)
    {
        $this->Router = $router;
    }
  
    public function loadRouterYML($file, $type = 'yml')
    {
        $dir = R::getSysCfg('OptionalFilesDir');
        $ymlFile = $dir . R::$DS . 'Routing' .  R::$DS . $file . '.' . $type;
        $this->RouterConfiguration = new ArrayLoader($ymlFile);
        $this->loadAll();
    }

    private function loadAll()
    {
        $this->loadPage();
        $this->loadFunctions();
    }
    
    private function loadPage()
    {
        $addPage = $this->RouterConfiguration->searchParentArray('AddPage'); 
        if (count($addPage) !== 0) 
        {
            foreach ($addPage as $k => $v)
            {
                if (!is_array($v))
                {
                    continue;
                }
                $this->Router->addPage("$k", $v);
            }
        }
    }
    
    private function loadFunctions()
    {
        $addFunction = $this->RouterConfiguration->searchParentArray('AddFunction');
        if (count($addFunction) !== 0)
        {
            foreach ($addFunction as $k => $v)
            {
                $this->Router->addFunction("$k", $v);
            }
        }       
    }
}
