<?php

namespace Frontelus\Library\I18N\JsonLocale;

use \Frontelus\Library\I18N\I18NParent;

class JsonLocale extends I18NParent
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
        return $this->langObject;
    }

    public function loadLang($domain, $lang)
    { 
        $this->errorId = 'E0';
        $this->documentPath = $this->tools->generatePath($this->tools->getLocalePath($lang, $domain, 'json'));

        if (!is_file($this->documentPath))
        {
            $this->documentPath = $this->tools->generatePath($this->tools->getLocalePath(FALSE, $domain, 'json'));
        }

        if (!is_file($this->documentPath))
        {
            $this->errorId = 'E_NOT_EXIST';
            return $this->errorId;
        }

        try
        {
            $value = $this->parse_json();
            if (is_array($value))
            {
                $this->langObject = $value;
            }
            else
            {
                $this->errorId = 'E_JSON_FORMAT';
            }
        } 
        catch (\Exception $e)
        {
            $this->errorId = 'E_Open_JSON';
        }

        return $this->errorId;
    }

    private function parse_json()
    {
        $langStr = file_get_contents($this->documentPath); 
        $langObject = json_decode($langStr, true); 
        $container = array();
        
        if (count($langObject) == 0)
        {
           return FALSE;
        }
        
        if (!isset($langObject['Locale']))
        {
            return FALSE;
        }
        
        foreach($langObject as $line)
        {
            foreach($line as $translation)
            {
                if (!isset($translation['msgid']) || !isset($translation['msgstr']))
                {
                    #error? 
                    continue;
                }

                $id = $translation['msgid'];
                $value = $translation['msgstr'];

                $container[$id] = $value;
            }
        }
        return $container;
    }

}
