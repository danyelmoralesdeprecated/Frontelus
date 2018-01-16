<?php

namespace Frontelus\Component\FRML\Core\Commands;

use Frontelus\Library\Collection;

class Command1 extends SuperCommand
{

    private $embeddedData;
    private $compoundData;
    private $usePlaceHolder;
    private $cfgStack;
    
    public function run($args)
    {
        if (empty($args))
        {
            return FALSE;
        }
        $this->compose($args);
    }

    private function compose($args)
    {
        if ((array_key_exists('in', $args)))
        {
            $this->in($args['in']);
        }
        
        if ((array_key_exists('with', $args)))
        {
            $this->with($args['with']);
        }
        
        if ((array_key_exists('ph', $args)))
        {
            $this->ph($args['ph']);
        }

        $this->data = $this->compoundData;

        return TRUE;
    }

    public function getCompoundData()
    {
        if (!empty($this->compoundData))
        {
            return $this->compoundData;
        }
    }

    private function in($value)
    {
        if ($this->tools->isFileOrText($value))
        {
            $value = $this->tools->cleanValue($value);
            if (strlen($value) !== 0)
            {
                $this->compoundData = $this->tools->getContentByType($value);
            }
            $this->usePlaceHolder = TRUE;
        } else
        {
            $this->usePlaceHolder = FALSE;
            $this->compoundData = $value;
        }
    }

    private function ph($placeHolder)
    {
        $this->compoundData = $this->tools->setContent($placeHolder, $this->embeddedData, $this->compoundData, $this->usePlaceHolder);
    }

    private function with($value)
    {
        if ($this->tools->isFileOrText($value))
        {
            $value = $this->tools->cleanValue($value);
            $this->embeddedData = $this->tools->getContentByType($value);
        } 
        else
        {
            $this->embeddedData = $value;
        }
    }
    
    private function cfgCompundArrData()
    {
        
    }

}
