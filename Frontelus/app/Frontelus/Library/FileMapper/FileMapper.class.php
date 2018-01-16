<?php

namespace Frontelus\Library\FileMapper;

use Frontelus\Library\Collection;
use Frontelus\R;

abstract class FileMapper
{

    protected $DocumentName;
    protected $DocumentInstance;
    protected $NSAllowed;
    protected $DocumentNameSpace;
    protected $Tools;
    protected $IsFileLoaded;
    
    public function __construct()
    {
        $this->Tools = new Collection(R::$APP_PATH . R::$DS);
        $this->NSAllowed = array(
            'LayoutAliasFileMapper',
            'LayoutFileMapper' 
        );
        $this->IsFileLoaded = false;
    }

    public function mapFile($file)
    {
        $xmlFile = R::getSysCfg('OptionalFilesDir') . R::$DS . "Maps" . R::$DS . $file ;
        
        if(!$this->Tools->fileExists($xmlFile))
        {
             $this->IsFileLoaded = false;
             return null;
        }
        
        $xmlObj = $this->loadXMLMap($xmlFile);
        
        if ($xmlObj === NULL || !(is_object($xmlObj)))
        {
            // nuevo error
            $this->IsFileLoaded = false;
            return false;
        }

        $nsxml = $xmlObj->getName();
        
        if (!in_array($nsxml, $this->NSAllowed))
        {
            // nuevo error
            $this->IsFileLoaded = false;
            return false;
        }

        $this->DocumentName = $xmlFile;
        $this->DocumentInstance = $xmlObj;
        $this->DocumentNameSpace = $nsxml;
        $this->IsFileLoaded = true;
        
        return true;
    }

    public function getFileArrByCol($col, $val)
    {
        $val = preg_replace("/[^A-Za-z0-9 ]/", '', $val);
        $xpath = '/' . $this->DocumentNameSpace . '/file[@' . $col . '=\'' . $val . '\']';
        $attr = array();

        if ($this->DocumentInstance->xpath($xpath))
        {
            $attr = $this->DocumentInstance->xpath($xpath);
        }
        
        return $attr;
    }

    // ojo tratar errores, recomendado        libxml_use_internal_errors(false);
    private function loadXMLMap($map)
    {
        $ext = $this->Tools->getExtension($map);
        $data = NULL;

        if ($ext === 'php')
        {
            $data = simplexml_load_string($this->Tools->getIncludedContent($map));
        }
        elseif ($ext === 'xml')
        {
            $data = simplexml_load_file($this->Tools->generatePath($map));
        }

        return $data;
    }

    // bajo revisión fileAliasMapper
    private function agregarCoordenada($map, $code, $public = true)
    {
//        $mapTmp = preg_replace("/[^A-Za-z0-9 ]/", '', $map);
//        $map = $this->Tools->filtrarFileName($mapTmp);
//        $obj = $this->InstanciaDocumento->addChild("file", "$code");
//        $obj->addAttribute('map', $map);
//        $obj->addAttribute('public', $public);
//        $dom = new \DomDocument('1.0');
//        $dom->preserveWhiteSpace = false;
//        $dom->formatOutput = true;
//        $dom->loadXML($this->InstanciaDocumento->asXML());
//        $dom->save($this->NombreDocumento);
    }

    // bajo rvisión fileAliasMapper
    private function set_helyKey($helyKey)
    {
        $this->GeneralHelyKey = $helyKey;
    }

}
