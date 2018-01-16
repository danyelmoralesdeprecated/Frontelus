<?php

namespace Frontelus\Library\I18N\IniLocale;

use \Frontelus\Library\I18N\I18NParent;

class IniLocale extends I18NParent
{

    private $documentPath;
    private $langObject;

    public function __construct()
    {
        parent::__construct();
    }

    public function getALL()
    {
        // NOT SUPPORTED
    }

    public function translate($value, $type)
    {
        $translatedValue = '';

        switch ($type)
        {
            case 'id':
                $translatedValue = $this->translate_id($value);
                break;

            case 'str':
                $this->errorId = 'NOT_SUPPORTED';
                break;

            case 'arr':
                $translatedValue = $this->getAllByArray();
                break;

            default:
                $this->errorId = 'E_T_UNK';
        }

        return $translatedValue;
    }

    public function translate_id($id)
    {
        if (isset($this->langObject[$id]))
        {
            return $this->langObject[$id];
        }
        return $id;
    }

    public function getAllByArray()
    {
        $container = array();

        foreach ($this->langObject['Locale']['id'] as $value => $msg)
        {
            $container["$value"] = (String) $msg;
        }
        return $container;
    }

    public function loadLang($domain, $lang)
    { 
        $this->errorId = 'E0';
        $this->documentPath = $this->tools->generatePath($this->tools->getLocalePath($lang, $domain, 'ini'));

        if (!is_file($this->documentPath))
        {
            $this->documentPath = $this->tools->generatePath($this->tools->getLocalePath(FALSE, $domain, 'ini'));
        }

        if (!is_file($this->documentPath))
        {
            $this->errorId = 'E_NOT_EXIST';
            return $this->errorId;
        }

        try
        {
            $value = $this->parse_ini();
            if (is_array($value))
            {
                $this->langObject = $value;
            }
            else
            {
                $this->errorId = 'E_INI_FORMAT';
            }
        } 
        catch (\Exception $e)
        {
            $this->errorId = 'E_Open_INI';
        }

        return $this->errorId;
    }

    private function parse_ini()
    {
        $langObject = parse_ini_file($this->documentPath, true); 
        if (count($langObject) == 0)
        {
           return FALSE;
        }
        
        if (!isset($langObject['Locale']))
        {
            return FALSE;
        }
        
        if (!isset($langObject['Locale']['id']))
        {
            return FALSE;
        }

        return $langObject;
    }

}
