<?php

namespace Frontelus\View;
use Frontelus\View\Universal\Console;
use Frontelus\R;

abstract class FacadeView
{
    protected $Layout;
    protected $View;
    protected $Messenger;
    protected $Message;
    protected $Objects;
    
    public function __construct()
    {
        Console::main($this);
        $this->Messenger  = R::getSysO('Messenger');
        $this->Layout = new FrontelusLayout();
        $this->View = new FrontelusView($this->Layout);
        $this->Objects = array();
        $this->initializeSys();
        $this->main();
    }

    public function addCfg($index, $param)
    {
        if (!($index || $param))
        {
            return false;
        }
        $this->Layout->addCfg($index, $param);
        return true;
    }

    public function addContent($index, $param)
    {
        if (!($index || $param))
        {
            return false;
        }
        $this->Layout->addContent($index, $param);
        return true;
    }

    public function addFile($index, $param)
    {
        if (!($index || $param))
        {
            return false;
        }
        $this->Layout->addFile($index, $param);
        return true;
    }

    public function addFileContent($index, $param)
    {
        if (!($index || $param))
        {
            return false;
        }
        $this->Layout->addFileContent($index, $param);
        return true;
    }
    
    public function addRegisteredFile($index, $param, $xmlLayout = '')
    {
        if (!($index || $param))
        {
            return false;
        }
        $this->Layout->addRegisteredFile($index, $param, $xmlLayout);
        return true;
    }

    public function addReference($index, $param)
    {
        if (!($index || $param))
        {
            return false;
        }
        $this->Layout->addReference($index, $param);
        return true;
    }
    
    public function deleteHtmlResource($type, $param, $direction = '')
    {
        $this->Layout->deleteHtmlResource($type, $param, $direction);
    }
    
    public function showView()
    {
       return $this->View->showView();
    }

    public function setDefaultLayout($layout)
    {
        $this->Layout->setDefaultLayout($layout);
    }

    public function setDirLayout($dir, $concat = false)
    {
        $this->Layout->setDirLayout($dir, $concat);
    }

    public function setLayout($layout)
    {
        $this->Layout->setLayout($layout);
    }
    
    public function turnOff()
    {
         $this->View->turnOff();
    }
    
    public function turnOn()
    {
         $this->View->turnOn();
    }
    
    public function turnOnI18N()
    {
        $I18N = R::getSysO('I18N');
        $this->View->turnOnI18N($I18N);
        $this->Objects['I18N'] = $I18N;
    }
    
    public function turnOffI18N()
    {
        $this->View->turnOffI18N();
    }
    
    public function getMessage()
    {
        if ($this->Message === null)
        {
            return '';
        }

        return $this->Message;
    }

    public function setMessage($value)
    {
        $this->Message = $value;
    }
    
    public function initializeSys()
    {
        $this->addCfg('R.indexPath', R::getIndexPath());
    }

    public function parseArray($data, array $array, $concat = '')
    {
        return $this->View->parseArray($data, $array, $concat);
    }
    
    public function getLayoutContent($layout)
    {
        return $this->View->getLayoutContent($layout);
    }
    
    public function flushView($data)
    {
        $this->View->flushView($data);
    }
    
    /*Should be improved*/
    public function load($index, $ext, $fx)
    {
        $dir = R::getSysCfg('OptionalFilesDir');
        $ymlFile = $dir . R::$DS . 'Configs' .  R::$DS . 'load' . '.' . $ext;
        $arrContent = new \Frontelus\Config\ArrayLoader($ymlFile);
        $indexArr = $arrContent->searchParentArray($index);
        $fx($indexArr, $this);
    }
    
    /* methods to be executed*/
    abstract public function main();
}
