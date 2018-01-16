<?php

namespace Frontelus;

use Frontelus\Library\Router;
use Frontelus\Library\Requests;
use Frontelus\View\FacadeView;
use Frontelus\R;

abstract class FrontelusMain
{

    protected $Router;
    protected $Request;
    protected $View;
    
    public function __construct(FacadeView $view, $model = NULL)
    {
        # loading configuration to the system
        R::setViewDirName($view);
        $this->View = $view;
        
        # initializing needed objects
        $this->Router = new Router\FrontalRouter($view, $model);
        $this->Request = new Requests\RequestSTD(R::getGlobalCfg()->searchArray('request'));
        $this->main();
    }

    public function start()
    {
        # routing and displaying the layouts
        $this->Router->_routePage($this->Request);
        $this->View->flushView($this->View->showView());       
    }
    
    public function setLang($sys, $lang = 'default', $helper = FALSE)
    {
        R::ActivateI18N($sys, $lang, $helper);
        $this->View->turnOnI18N();
        return $this;
    }
    
    public function setLangDependency(array $domain = array(), $say = FALSE)
    {
        $I18N = R::getSysO('I18N');
        if ($I18N !== '')
        {
            $I18N->loadFile($domain, $say);
        }
    }
    
    public function resetSection($cfg, $site)
    {
        R::initialize($site);
        R::loadCfg($cfg);
        $this->Request->restart(R::getGlobalCfg()->searchArray('request'));
    }
    
    public function turnOffLang()
    {
        $this->View->turnOffI18N();
    }

    abstract public function main();
}
