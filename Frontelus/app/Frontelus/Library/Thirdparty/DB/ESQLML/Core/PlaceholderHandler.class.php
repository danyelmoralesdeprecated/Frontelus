<?php
/*
 * =============================================================================
 * Author: Daniel V. Morales ( danyelmorales1991@gmail.com )
 * visit me: www.danyelmorales.com
 * =============================================================================
 *                               LICENSE GPL 
 * =============================================================================
    This file is part of ESQLML.

    ESQLML is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    ESQLML is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with ESQLML.  If not, see <http://www.gnu.org/licenses/>.
 * =============================================================================
 */
# falta, agregar soporte recursivo
namespace Frontelus\Library\Thirdparty\DB\ESQLML\Core;

class PlaceholderHandler
{

    private $symbolTable_inputs;
    private $stringToParse;
    private $placeholder_inputs;

    public function construct()
    {
        $this->symbolTable_inputs = array();
        $this->placeholder_inputs = array();
    }

    public function parseCondition(array $haystack)
    {
        $this->initialize($haystack);
        $this->genInputs($haystack);
    }
    
    private function initialize($haystack)
    {
        $this->symbolTable_inputs = array();
        $this->placeholder_inputs = array();
        $this->stringToParse = $haystack;
    }
    
    private function genInputs(array $inputsMatch)
    {
        foreach ($inputsMatch as $key => $value)
        {
            $type = $this->getType($value);
            $this->recordInput($key,$type);
        }
    }

    private function recordInput($key, $typeVal)
    {
        $ph = '';
        do
        {
            $ph = ':H' . $this->createNip(5);
            $isValidName = $this->isValidPhName($ph);
        } while (!$isValidName);
        $this->symbolTable_inputs[] = array($ph, $key, $typeVal);
        $this->placeholder_inputs[] = $ph;
    }

    private function getType($value)
    {
        $data = 'STR';
        if (gettype($value) === 'boolean')
        {
            $data = 'BOOL';
        } elseif (gettype($value) === 'integer')
        {
            $data = 'INT';
        } 

        return $data;
    }
    
    private function createNip($cut = false)
    {
        $time = (date('U'));
        $timesh = str_shuffle($time);
        $count = strlen($time);
        $arraytime = str_split($time);
        $arraytimesh = str_split($timesh);

        $buffer = "";
        $buffer1 = "";

        foreach ($arraytime as $value)
        {
            $buffer .= $buffer1;
            foreach ($arraytimesh as $value2)
            {
                $buffer1 = $value + $value2;
            }
        }

        $buffer *= $count;
        $ran = str_shuffle(rand(0, 514370487));
        list($usec, $sec) = explode(' ', microtime());

        $operTemp = abs($ran - $buffer);
        $oper = str_shuffle((sqrt($operTemp) / $usec) + $sec) + $count;
        $result = str_shuffle(base_convert($oper, 10, 16));
        $count2 = strlen($result);

        if ($cut != false)
        {
            if ($cut < $count2)
            {
                $result = substr($result, 0, $cut);
            }
        }

        return $result;
    }

    private function isValidPhName($name)
    {
        $isValidName = true;
        if (count($this->symbolTable_inputs) !== 0):
            foreach ($this->symbolTable_inputs as $value)
            {
                if ($value[0] === $name)
                {
                    $isValidName = false;
                    break;
                }
            }
        endif;
        return $isValidName;
    }

    public function getPlaceholder_inputs()
    {
        return $this->placeholder_inputs;
    }
    
    public function getSymbolTable_inputs()
    {
        return $this->symbolTable_inputs;
    }
}
