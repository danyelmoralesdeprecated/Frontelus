<?php

namespace Frontelus\Config;

use \Frontelus\Config\ArrayLoader;
use \Frontelus\Library\Dictionary;
use \Frontelus\Library\Messenger;
use \Frontelus\R;

class ConfigHelper
{
    private $FrontelusConfiguration;
    private $CommonConfiguration;
    private $GlobalConfiguration;
    private $SysObj;
    
    public function __construct($cfg)
    {
        $this->CommonConfiguration = new Dictionary();
        $this->FrontelusConfiguration = new Dictionary();
        $this->SysObj = new Dictionary();
        
        $this->loadSysCfg();
        $this->loadCfg($cfg);
    }
 
    /**
     * Default sys configuration variables
     *
     * @since 0.0.2
     */
    private function loadSysCfg()
    {
        $this->FrontelusConfiguration->setDefinition_word('ViewPathLayout',     'Layouts');
        $this->FrontelusConfiguration->setDefinition_word('OptionalFilesDir',   'Data');
        $this->FrontelusConfiguration->setDefinition_word('CommonDir',          'Common');
        $this->FrontelusConfiguration->setDefinition_word('ViewDir',            'Common');
     
        /* fxFile It's okay but plz you shouldn't use it*/
        $this->FrontelusConfiguration->setDefinition_word('fxFile',             'fx.php');   
        $this->SysObj->setDefinition_word('Messenger', new Messenger());
    }

    /**
     * Loading sys configuration file
     *
     * @since 0.0.2
     */
    private function loadCfg($cfg)
    {
        $this->GlobalConfiguration = new ArrayLoader(R::$Microsite . $cfg);
        
        if($this->GlobalConfiguration->getIsError())
        {
            die();
        }
        
        $resources = $this->GlobalConfiguration->searchArray('resources');

        if (count($resources) > 0)
        {
            $this->loadCfgAttr('helperFiles', $resources, $this->FrontelusConfiguration);
            $this->loadCfgAttr('ViewPathLayout', $resources, $this->FrontelusConfiguration);
            $this->loadCfgAttr('OptionalFilesDir', $resources, $this->FrontelusConfiguration);
        }
    }

    private function loadCfgAttr($index, $array, &$attr)
    {
        if (array_key_exists($index, $array))
        {
            $attr->setDefinition_word($index, $array["$index"]);
        }
    }
    
    /**
     * Getters of configuration
     *
     * @since 0.0.2
     */
    public function getGlobalConfiguration()
    {
        return $this->GlobalConfiguration;
    }

    public function getSysCfg($index)
    {
        return $this->FrontelusConfiguration->getDefinition_word($index);
    }
    
    public function getSysO($index)
    {
        return $this->SysObj->getDefinition_word($index);
    }
    
    public function setSysO($index, $component)
    {
        if (is_object($component) && !$this->SysObj->indexExists_word($index))
        {
            $this->SysObj->setDefinition_word($index, $component);
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Adding a configuration param to sys config
     *
     * @since 0.0.2
     */
    public function setSysCfg($index, $param)
    {
        if($param !== '')
        {
            $this->FrontelusConfiguration->setDefinition_word($index, $param);
        }
    }
}
