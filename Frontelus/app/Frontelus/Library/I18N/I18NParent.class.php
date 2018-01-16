<?php

namespace Frontelus\Library\I18N;

use Frontelus\Library\I18N\InterfaceI18N;
use Frontelus\Library\Collection;

abstract class I18NParent implements InterfaceI18N
{

    protected $errorMessage;
    protected $errorId;
    protected $tools;
    protected $language;
    protected $legacy;
    
    public function __construct()
    {
        $this->errorId = 'E0';
        $this->errorMessage = array('E0' => 'NO ERROR',);
        $this->tools = new Collection();
        $this->legacy = array(
            'fileLoad' => FALSE,
            'wordLoad' => TRUE,
            'objReturn' => FALSE
        );
    }

    public function setLanguage($lang)
    {
        $this->language = $lang; 
    }

    public function getTranslations($type, array $strids)
    {
        $container = array();
        foreach ($strids as $strid)
        {
            $container[$strid] = $this->translate($strid, $type);
        }
        return $container;
    }

    public function getErrorId()
    {
        return $this->errorId;
    }

    public function getErrorMessage($id)
    {
        if (isset($this->errorMessage[$id]))
        {
            return $this->errorMessage[$id];
        }
        return '';
    }

    /**
     *  @Group/id
     */
    public function parseStrRequest($str)
    {
        $value = array();
        if (strlen($str) > 0)
        {
            if ($str[0] === '@')
            {
                $value = explode('/', rtrim($str));
            }
        }
        return $value;
    }

    public function setDomain($domain)
    {
        $eID = $this->loadLang($domain, $this->language);
        return $eID;
    }

    public function validateResult($valueTranslated)
    {
        if ($valueTranslated == '')
        {
            $this->errorId = $this->getErrorId();
            return FALSE;
        }
        $this->errorId = 'E0';
        return $valueTranslated;
    }

    public function getLegacy($index)
    {
        if(isset($this->legacy[$index]))
        {
          return $this->legacy[$index];
        }
    }
    
    public function setLegacy($index, $value)
    {
        if(isset($this->legacy[$index]))
        {
            $this->legacy[$index] = $value;
        }
    }
    
    public function extendLegacyArry($legacy)
    {
        if (is_array($legacy))
        {
            $this->legacy = array_merge($this->legacy, $legacy);
        }
    }
    
}
