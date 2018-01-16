<?php

namespace Frontelus\Config;

use Frontelus\Library\Collection;
use Frontelus\R;

final class ArrayLoader
{

    private $Array;
    private $Tools;
    private $Error;

    public function __construct($configuration)
    {
        $this->Tools = new Collection();
        $this->loadArray($configuration);
    }

    public function loadArray($configuration)
    {
        $this->Error = false;
        if (is_array($configuration))
        {
            $this->Array = $configuration;
        } 
        elseif (is_file(R::$APP_PATH . R::$DS . $configuration))
        {
            $this->Array = $this->loadYML($configuration);
            if (count($this->Array) === 0)
            {
                $this->Error = true;
            }
        } 
        else
        {
            $this->Error = true;
        }
    }

    private function loadYML($configuration)
    {
        $ext = $this->Tools->getExtension($configuration);
        $data = array();

        if ($ext === 'php')
        {
            $dataTmp = $this->Tools->getContentByType($configuration);
            $data = $this->Tools->getYmlContent($dataTmp);
        } 
        elseif ($ext === 'yml')
        {
            $data = $this->Tools->getContentByType($configuration);
        }

        return $data;
    }

    public function searchArray($index, $type = 'configuration')
    {
        if (!$this->Error)
        {
            if (is_array($this->Array) && array_key_exists($index, $this->Array[$type]))
            {
                return $this->Array[$type][$index];
            }
        }

        return array();
    }

    public function search($index, $type = 'configuration', $div = ':')
    {
        if (!$this->Error && array_key_exists($index, $this->Array[$type]))
        {
            $data = '';
            foreach ($this->Array[$type][$index] as $k => $v)
            {
                $data .= $k . "$div" . $v . ',';
            }
            return rtrim($data);
        }
        return null;
    }

    public function searchParentArray($index)
    {
        if (!$this->Error)
        {
            if (array_key_exists($index, $this->Array))
            {
                return $this->Array[$index];
            }
        }
        return Array();
    }

    public function getIsError()
    {
        return $this->Error;
    }
}
