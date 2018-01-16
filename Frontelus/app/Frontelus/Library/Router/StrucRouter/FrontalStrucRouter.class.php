<?php

namespace Frontelus\Library\Router\StrucRouter;

use Frontelus\View\FacadeView;
use Frontelus\Library\Collection;
use \Frontelus\Library\Dictionary;
use Frontelus\R;

class FrontalStrucRouter
{

    private $View;
    private $Tools;
    private $Request;
    private $Dir;
    private $BaseName;
    private $FileName;
    private $Path;
    private $FxFile;
    private $StrucFunctions;
    
    public function __construct(FacadeView $view)
    {
        $this->View = $view;
        $this->Tools = new Collection();
        $this->Request = array();
        $this->Dir = '';
        $this->BaseName = '';
        $this->FileName = '';
        $this->Path = '';
        $this->FxFile = '';
        $this->StrucFunctions = new Dictionary();
    }

    public function routeStructured($request)
    {
        #$this->View->turnOff();
        $this->Request = $request;
        $this->genPagePath();
        $this->loadStructuredFile();
    }

    private function genPagePath()
    {
        if (!isset($this->Request[1]))
        {
            return '';
        }

        $baseName = $this->Tools->filterGetPage($this->Request[1], true);
        $dir = 'Pages' . R::$DS . $baseName;
        $filename = '';

        if (isset($this->Request[2]))
        {
            $section = $this->Tools->filterGetPage($this->Request[2], true);
            if ($section === 'fx'){die('there is no such section.');}
            $filename = $baseName . '-' . $section . '.php';
        }
        else
        {
            $filename = $baseName . '.php';
        }

        $this->FxFile = $this->Tools->generatePathView($dir, $baseName . '-fx.php');
        $this->Dir = $dir;
        $this->BaseName = $baseName;
        $this->FileName = $filename;
        $this->Path = $this->Tools->generatePathView($dir, $filename);
    }

    private function loadStructuredFile()
    {
        if ($this->Path === '')
        {
            die('There is no such page!');
        }

        $this->Tools->includeFIle($this->FxFile);
        $this->executeFunction();
        $this->View->setDirLayout($this->Dir);
        $this->View->setLayout($this->FileName);
    }

    private function executeFunction()
    {   
        if (isset($this->Request[3]))
        {
            $publicFx = $this->Tools->filterGetPage($this->Request[3], true);
            $letitbe = $this->StrucFunctions->getDefinition_word($publicFx);
            if ( $letitbe !== '' && function_exists($letitbe))
            {
                call_user_func($letitbe);
            }
        }
    }
    
    public function addFunction($index, $param)
    {
        if (!($index || $param))
        {
            return FALSE;
        }
        $this->StrucFunctions->setDefinition_word($index, $param);
    }
}
