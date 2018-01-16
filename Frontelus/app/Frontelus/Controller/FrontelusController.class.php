<?php

// otorga la funcionalidad necesaria para comunicar 
// configuraciones a la vista

namespace Frontelus\Controller;

use \Frontelus\R;

abstract class FrontelusController
{

    protected $View;
    protected $Model;
    protected $Messenger;
    
    public function __construct($view, $model)
    {
        $this->View = $view;
        $this->Model = $model;
        $this->Messenger  = R::getSysO('Messenger');
        $this->onLoad();
    }

    abstract public function onLoad();
}
