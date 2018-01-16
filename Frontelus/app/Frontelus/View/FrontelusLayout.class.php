<?php

namespace Frontelus\View;

use Frontelus\Library\Dictionary;
use Frontelus\Library\FileMapper\LayoutFileMapper;
use Frontelus\View\ResourceLoader;
use Frontelus\R;

class FrontelusLayout
{

    private $CfgDictionary;
    private $ContentDictionary;
    private $FileDictionary;
    private $FileContentDictionary;
    private $LayoutFileMapper;
    private $Layout;
    private $DirLayout;
    private $DefaultLayout;
    private $Resource;
    
    public function __construct()
    {
        defined('HelyFlag') or die(0x1992);
        $this->CfgDictionary = new Dictionary();
        $this->ContentDictionary = new Dictionary();
        $this->FileDictionary = new Dictionary();
        $this->FileContentDictionary = new Dictionary();
        $this->LayoutFileMapper = new LayoutFileMapper();
        $this->Resource = new ResourceLoader();
        $this->DirLayout = R::getSysCfg('ViewPathLayout');
    }

    /**
     * =========================================================================
     *                      CONTENT CREATION GROUP
     * =========================================================================
     * @desc write content in base layout by replacing a place holder.
     * examples include $obj->addX('helloWorldPh', 'Some Stuff');
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required Dictionary memory location
     * @param String $index Place holder to be replaced
     * @param type $param Content used to replace the place holder
     * @return void 
     */
    public function addCfg($index, $param)
    {
        if (empty($index) && empty($param))
        {
            return false;
        }
        $this->CfgDictionary->setDefinition_word($index, $param);
        return true;
    }

    public function addContent($index, $param)
    {
        if (empty($index) && empty($param))
        {
            return false;
        }
        $this->ContentDictionary->setDefinition_word($index, $param);
        return true;
    }

    public function addFile($index, $param)
    {
        if (empty($index) && empty($param))
        {
            return false;
        }
        $this->FileDictionary->setDefinition_word($index, $param);
        return true;
    }
    
    public function addFileContent($index, $param)
    {
        if (empty($index) && empty($param))
        {
            return false;
        }
        $this->FileContentDictionary->setDefinition_word($index, $param);
        return true;
    }

    public function addRegisteredFile($index, $param, $xmlLayout = '')
    {
        if (empty($index) && empty($param))
        {
            return false;
        }
        
        if(!$this->LayoutFileMapper->getXMLFileStat() || $xmlLayout !== '')
        {
              $this->LayoutFileMapper->mapFile($xmlLayout);
        }
        
        $this->LayoutFileMapper->evalXMLLayout($index, $param, $this);
        return true;
    }

    public function addHtmlResource($type, $param)
    {
        if (empty($type) && empty($param))
        {
            return false;
        }
        $this->Resource->addToArrayRes($type, $param);
        return true;
    }
    
    public function addHtmlResource_custom($type, $param, $alias)
    { 
        if (empty($type) && empty($param) && empty($alias))
        {
            return false;
        }
        $this->Resource->addToArrayRes_custom($type, $param, $alias);
        return true;
    }

    public function deleteHtmlResource($type, $param, $direction = '')
    {
        if (empty($type) && empty($param))
        { 
            return false;
        }
        $this->Resource->deletPhidFromArray($type, $param, $direction);
    }
    
    /**
     * =========================================================================
     *                      CONTENT CREATION GETTER GROUP
     * =========================================================================
     * @desc get content buffered.
     * examples include $obj->getX();
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required Dictionary memory location
     * @return array  
     */

    public function getCfg()
    {
        return $this->CfgDictionary->getWords();
    }

    public function getContent()
    {
        return $this->array_filter($this->ContentDictionary->getWords());
    }

    public function getFile()
    {
        return $this->array_filter($this->FileDictionary->getWords());
    }

    public function getContentFile()
    { 
        return $this->array_filter($this->FileContentDictionary->getWords());
    }
    
    public function getHtmlResources()
    {
        return $this->Resource;
    }

    /**
     * =========================================================================
     *                      CONTENT CREATION COUNTER GROUP
     * =========================================================================
     * @desc get the number of existing elements buffered.
     * examples include $obj->getXFlag();
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required Dictionary memory location
     * @return int 
     */

    public function getCfgFlag()
    {
        return count($this->CfgDictionary->getWords());
    }

    public function getContentFlag()
    {
        return count($this->ContentDictionary->getWords());
    }

    public function getFileFlag()
    {
        return count($this->array_filter($this->FileDictionary->getWords()));
    }

    public function getFileContentFlag()
    {
        return count($this->array_filter($this->FileContentDictionary->getWords()));
    }
    
    public function getHtmlResourcesFlag()
    {
        return  count($this->array_filter($this->Resource->getArrayRes()));
    }

    /**
     * =========================================================================
     *                      MORE GETTERS GROUP
     * =========================================================================
     * @desc get some stuff.
     * examples include $obj->getX();
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required 
     * @return mixed 
     */
    public function getLayout()
    {
        if ($this->Layout === null)
        {
            return $this->DefaultLayout;
        }

        return $this->Layout;
    }

    public function getDirLayout()
    {
        return $this->DirLayout;
    }
    
    public function setDirLayout($value, $concat = false)
    {
        if ($value !== '')
        {
            $this->DirLayout = ($concat) ? $this->DirLayout . R::$DS . $value : $value;
        }
    }
    
    public function setLayout($layout)
    {
        if ($layout !== '')
        {
            $this->Layout = $layout;
        } 
    }

    public function setDefaultLayout($value)
    {
        if ($value !== '')
        {
            $this->DefaultLayout = $value;
        }
    }
    
    /**
     * =========================================================================
     *                      Helper Methods
     * =========================================================================
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required 
     * @return mixed 
     */
    public function array_filter($array)
    {
        return array_filter($array, 'strlen');
    }
}
