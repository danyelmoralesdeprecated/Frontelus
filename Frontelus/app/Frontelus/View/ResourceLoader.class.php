<?php

namespace Frontelus\View;

use Frontelus\Library\Dictionary;

class ResourceLoader
{

    private $ArrayRes;
    private $ArrayPhid;
    private $ArrayResStringify;
    private $ArrayTypeAllowed;
    private $ArrayType;

    public function __construct()
    {
        $this->ArrayRes = new Dictionary();
        $this->ArrayPhid = new Dictionary();
        $this->ArrayResStringify = new Dictionary();
        $this->ArrayTypeAllowed = new Dictionary();
        $this->init();
    }

    private function init()
    {
        $this->ArrayTypeAllowed->setDefinition_word('js', '<script type="text/javascript" src="%{__RHERE__}%"></script>');
        $this->ArrayTypeAllowed->setDefinition_word('css', '<link rel="stylesheet" type="text/css" href="%{__RHERE__}%">');
    }

    public function addToArrayRes($type, $file)
    {
        $this->ArrayRes->pushDefinition_wordBuffer("$type", $file);
    }

    public function addToArrayRes_custom($type, $file, $alias)
    {
        $container = new \stdClass();
        $container->type = $type;
        $container->file = $file;
        $this->ArrayRes->pushDefinition_wordBuffer("$alias", $container);
    }

    public function getArrayRes()
    {
        return array_filter($this->ArrayRes->getWordBuffer());
    }

    public function getArrayPhid()
    {
        return array_filter($this->ArrayPhid->getWords());
    }

    public function deletPhidFromArray($type, $value, $direction = '')
    {
        if ($direction !== '')
        { 
            $container = new \stdClass();
            $container->type = $type;
            $container->file = $value;
            $this->ArrayRes->deleteDefinition_wordBuffer($direction, $container);
        }
        else
        {
            $this->ArrayRes->deleteDefinition_wordBuffer($type, $value);
        }
    }
    
    public function getArrayResStringify($format = false)
    {
        if (!$this->ArrayResStringify->isUsed())
        {
            $this->createArrayResStringify($format);
        }
        return $this->ArrayResStringify->getWords();
    }

    private function createArrayResStringify($format = false, $arrayResource = array())
    {
        $arrayRes = (count($arrayResource) === 0 ) ? array_filter($this->ArrayRes->getWordBuffer()) : array_filter($arrayResource);
        foreach ($arrayRes as $key => $value)
        {
            $bufferTmp = '';
            $type = ''; 
            $template = $this->getTemplate($key, $value, $type);
            $phid = $this->createPhid($key, $type);
            foreach (array_filter($value) as $resource)
            {
                if ($format)
                {
                    $resource = str_replace('%{__RHERE__}%', $this->getFile($resource), $template) . "\n\t";
                }
                $bufferTmp .= $resource;
            }
            $this->ArrayPhid->setDefinition_word_NI($phid);
            $this->ArrayResStringify->setDefinition_word($phid, rtrim(rtrim($bufferTmp)));
        }
    }

    private function getTemplate(&$key, $value, &$type = '')
    { 
        if (is_object($value[0]) && ($value[0] instanceof \stdClass))
        {
            $type = (string) $key;
            $key = (string) $value[0]->type;
        }
        return $this->ArrayTypeAllowed->getDefinition_word($key);
    }

    private function getFile($value)
    {
        if (is_object($value) && ($value instanceof \stdClass))
        {
            $value = $value->file;
        }
        return $value;
    }

    private function createPhid($keyType, $type = '')
    {  
        if ($this->ArrayTypeAllowed->getDefinition_word("$keyType") === '')
        {  
            return '';
        }
        $ext = ($type !== '') ? $keyType . '.' . $type : $keyType;
        $phid = 'RES.' . $ext;
        return $phid;
    }

    public function render(array $content)
    {
        $contentArr = $this->createArrayResStringify(true, $content);
        return $contentArr;
    }

}
