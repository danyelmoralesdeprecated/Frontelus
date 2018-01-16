<?php

namespace Frontelus\Library\FileMapper;

use Frontelus\View\ResourceLoader;

class LayoutFileMapper extends FileMapper
{

    private $ChildStorage;
    private $Attributes;
    private $NSAttrAllowed;
    private $ChildAllowed;
    private $ChildAttributesAllowed;
    private $ChildTypesAllowed;
    private $Resource;

    public function __construct()
    {
        parent::__construct();
        $this->Attributes = array();
        $this->NSAttrAllowed = array('name', 'dirFile');
        $this->ChildAllowed = array('resource', 'alternative');
        $this->ChildAttributesAllowed = array('dirFile', 'type', 'direction');
        $this->ChildTypesAllowed = array('js', 'css'); // obtenerlo desde otra clase
        $this->ChildStorage = new ChildStorage();
        $this->Resource = new ResourceLoader();
    }

    public function getFileFromKey($key)
    {
        $this->ChildStorage->reset();
        if (!$this->IsFileLoaded)
        {
            return false;
        }

        $data = $this->getFileArrByCol('name', $key);

        if (count($data) === 0)
        {
            return false;
        }

        $this->checkAttributes($data);
        $this->checkChild($data);

        if (count($this->Attributes) === 0)
        {
            return false;
        }

        return true;
    }

    public function getChild()
    {
        return $this->ChildStorage->getStorage();
    }

    public function getAttr()
    {
        return $this->Attributes;
    }

    private function checkChild($objTag)
    {
        $index = 0;
        foreach ($objTag[0] as $value)
        {
            if (in_array($value->getName(), $this->ChildAllowed))
            {
                $this->checkChildAttributes($value, $value->getName(), $index);
            }
            $index++;
        }
    }

    private function checkChildAttributes($objTag, $type, $index)
    {
        foreach ($objTag->attributes() as $k => $v)
        {
            if (!in_array($k, $this->ChildAttributesAllowed))
            {
                break;
            } else
            {
                $this->ChildStorage->addToStorage($k, $v, $type, $index);
            }
        }
    }

    private function checkAttributes($objTag)
    {
        foreach ($objTag[0]->attributes() as $k => $v)
        {
            if (!in_array($k, $this->NSAttrAllowed))
            {
                $this->Attributes = array();
                break;
            } else
            {
                $this->Attributes["$k"] = $v;
            }
        }
    }

    /**
     * =========================================================================
     *                      evalXMLLayout
     * =========================================================================
     * @desc takes information from a xml file and configures the system
     * examples include $obj->evalXMLLayout();
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required 
     * @return bool 
     */
    public function evalXMLLayout($index, $param, $scope)
    {
        if (!$this->getFileFromKey($param))
        {
            return false;
        }

        $attr = $this->getAttr();
        $child = $this->getChild();

        if (count($attr) === 0)
        {
            return false;
        }

        $scope->addFile($index, $attr['dirFile']);

        if (count($child) > 0)
        {
            $this->evalChild($child, $scope);
        }

        return true;
    }

    private function evalChild($child, $scope)
    {
        foreach ($child as $key => $value)
        {
            switch ($key)
            {
                case 'resource':
                    $this->evalXMLLayoutResource($value, $scope);
                    break;
            }
        }
    }

    private function evalXMLLayoutResource(array $child, $scope)
    {
        for ($i = 0; $i < count($child['type']); $i++)
        {
            if (!in_array($child['type'][$i], $this->ChildTypesAllowed))
            {
                continue;
            }

            if (isset($child['direction'][$i]))
            {
                $scope->addHtmlResource_custom($child['type'][$i], $child['dirFile'][$i], $child['direction'][$i]);
                continue;
            }

            $scope->addHtmlResource($child['type'][$i], $child['dirFile'][$i]);
        }
    }

    public function getXMLFileStat()
    {
        return $this->IsFileLoaded;
    }

}
