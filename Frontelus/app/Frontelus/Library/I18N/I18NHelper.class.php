<?php

namespace Frontelus\Library\I18N;

use Frontelus\Library\Dictionary;
use Frontelus\Library\Factory;

class I18NHelper
{

    private $language;
    private $languageSystem;
    private $container;
    private $I18N;

    public function __construct($languageSystem, $language)
    {
        $this->languageSystem = $languageSystem;
        $this->language = $language;
        $this->container = array();
        $this->initialize();
    }

    public function initialize()
    {
        $this->I18N = Factory::build_I18N($this->languageSystem);
        $this->I18N->setLanguage($this->language);
        
        $this->container = array(
            'files' => new Dictionary(),
            'words' => new Dictionary(),
            'temp' => new Dictionary()
        );
    }

    # check legacy comp in lang sys

    public function loadFile(array $files, $say = FALSE)
    {
        if (!$this->I18N->getLegacy('fileLoad'))
        {
            if ($say){ throw new \Exception("File Load is not supported in this locale sys."); }
            return FALSE;
        }
        
        foreach ($files as $value)
        {
            $this->container['files']->setDefinition_word_NI($value);
        }
    }

    public function unloadFile(array $files)
    {
        foreach ($files as $value)
        {
            $this->container['files']->deleteDefinition_word($value);
        }
    }

    public function loadWord($word, $domain, $say = FALSE)
    {
        if ($say){throw new \Exception("Word load is not supported in this locale sys.");}
        $this->container['words']->setDefinition_word($word, $domain);
    }

    public function loadWords(array $words, $domain)
    {
        foreach ($words as $value)
        {
            $this->loadWord($value, $domain);
        }
    }

    public function loadWords_domains(array $words)
    {
        foreach ($words as $domain => $list)
        {
            $this->loadWords($list, $domain);
        }
    }

    public function getTranslationBuffer()
    {
        return $this->container;
    }

    public function translate($data)
    {
        $this->renderFile();
        $this->renderWord();
        $buffer = $data;
        $array = $this->container['temp']->getWords();

        if (count($array) > 0)
        {
            foreach ($array as $key => $value)
            {
                $buffer = str_replace('%{@' . $key . '}%', $value, $buffer);
            }
        }

        return $buffer;
    }

    private function renderFile()
    {
        if (!$this->I18N->getLegacy('fileLoad'))
        {
            // not supported
            return FALSE;
        }
        
        $files = $this->container['files']->getWords();
        
        if (count($files) == 0)
        {
            return "";
        }

        foreach ($files as $value)
        {
            $eID = $this->I18N->setDomain($value);

            if ($eID !== 'E0')
            {
                # throw error
                continue;
            }

            $file = $this->I18N->translate('', "arr");
            foreach ($file as $key => $translation)
            {
                $this->container['temp']->setDefinition_word($key, $translation);
            }
        }
        
        return TRUE;
    }

    private function renderWord()
    {
        if (!$this->I18N->getLegacy('wordLoad'))
        {
            // not supported
            return FALSE;
        }
        $words = $this->container['words']->getWords();

        if (count($words) == 0)
        {
            return "";
        }

        foreach ($words as $id => $domain)
        {
            $eID = $this->I18N->setDomain($domain);

            if ($eID !== 'E0')
            {
                # throw error
                continue;
            }

            $translated = $this->I18N->translate($id, "id");
            $this->container['temp']->setDefinition_word($id, $translated);
        }
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

    public function getError($retId = false)
    {
        $errorid = $this->I18N->getErrorId();
        if ($retId)
        {
            return $errorid;
        }

        if ($errorid !== 'E0')
        {
            return TRUE;
        }

        return FALSE;
    }

    public function getErrorMessage($id)
    {
        return $this->I18N->getErrorMessage($id);
    }

}
