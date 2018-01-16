<?php

namespace Frontelus\Library\I18N\XmlLocale;

use \Frontelus\Library\I18N\I18NParent;

class XmlLocale extends I18NParent
{

    private $documentPath;
    private $langObject;

    public function __construct()
    {
        parent::__construct();
        $this->errorMessage = array('E_T_UNK' => 'Translation type not supported', 
                                    'E_Namespace' => "The Language File is not well formed... Where is the namespace?",
                                    'E_Open_XML' => 'Ups! something went wrong while Jimmy was parsing the xml file for language translation',
                                    'E_Coming_Soon' => 'Ups! It is not available here yet',
                                    'E_NOT_EXIST' => 'Does not exist the file.');
   }

    public function loadLang($domain, $lang)
    {
        $this->errorId = 'E0';
        $this->documentPath = $this->tools->generatePath($this->tools->getLocalePath($lang, $domain, 'xml'));

        if (!is_file($this->documentPath))
        {
            $this->documentPath = $this->tools->generatePath($this->tools->getLocalePath(FALSE, $domain, 'xml'));
        }
        
        if (!is_file($this->documentPath))
        {
            $this->errorId = 'E_NOT_EXIST';
            return $this->errorId;
        }
        
        try
        { 
            $this->langObject = simplexml_load_file($this->documentPath);
            if ($this->langObject->getName() !== 'Locale')
            {
                $this->errorId = 'E_Namespace';
            }
        } 
        catch (\Exception $e)
        {
           $this->errorId = 'E_Open_XML';
        }
        
        return $this->errorId;
    }

    public function getALL()
    {
        return (Array) $this->langObject;
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
                $this->errorId = 'E_Coming_Soon';
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
        $id = preg_replace("/[^A-Za-z0-9 ]/", '', $id);
        $xpath = '/Locale/msgstr[@msgid="' . $id . '"]';
        $msgstr = $this->langObject->xpath($xpath);

        if (isset($msgstr[0]) && !empty($msgstr[0]))
        {
            return $msgstr[0];
        }

        return $id;
    }

    public function getAllByArray()
    {
        $container = array();
        
        foreach ($this->langObject->msgstr as $msg)
        {
            foreach ($msg->attributes() as $value)
            {
                $container["$value"] = (String) $msg;
            }
        }
        return $container;
    }

}
