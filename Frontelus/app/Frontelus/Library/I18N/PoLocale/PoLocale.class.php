<?php

namespace Frontelus\Library\I18N\PoLocale;

use \Frontelus\Library\I18N\I18NParent;

class PoLocale extends I18NParent
{

    private $documentPath;
    private $inited;
    
    public function __construct()
    {
        parent::__construct();
        $this->inited = FALSE;
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
                $this->errorId = 'NOT_SUPPORTED';
                break;

            default:
                $this->errorId = 'E_T_UNK';
        }

        return $translatedValue;
    }

    public function translate_id($id)
    {
        $response = "";
        $array = $this->parseStrRequest($id);
        if (count($array) == 0)
        {
            $response = _($id); 
        }
        else
        {
            $response = dgettext($array[0], $array[1]);
        }
        return $response;
    }
    
    public function loadLang($domain, $lang)
    {
        $this->errorId = 'E0';
        $this->documentPath = $this->tools->generatePath($this->tools->getLocalePath($lang, $domain, 'po'));

        if (!is_file($this->documentPath))
        {
            $this->documentPath = $this->tools->generatePath($this->tools->getLocalePath(FALSE, $domain, 'po'));
        }

        if (!is_file($this->documentPath))
        {
            $this->errorId = 'E_NOT_EXIST';
            return $this->errorId;
        }

        try
        {
            if(!$this->inited)
            {
                putenv("LANG=" . $lang);
                setlocale(LC_ALL, $lang);
                textdomain($domain);
                $this->inited = TRUE;
            }
            
            $localePath = dirname(dirname(dirname($this->documentPath)));
            bindtextdomain($domain, $localePath);
            bind_textdomain_codeset($domain, 'UTF-8');
        }
        catch (\Exception $e)
        {
            $this->errorId = 'E_Open_XML';
        }

        return $this->errorId;
    }

}
