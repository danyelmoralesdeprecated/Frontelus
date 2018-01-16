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
namespace Frontelus\Library\Thirdparty\DB\ESQLML\Lib;

final class Dictionary
{

    private $WordBuffer;
    private $Word;
    private $Index;

    public function __construct()
    {
        $this->Index = 0;
        $this->WordBuffer = array();
        $this->Word = array();
    }

    public function setDefinition_word($word, $definition)
    {
        if ($word)
        {
            $this->Word[$word] = $definition;
        }
    }

    public function setDefinition_word_NI($definition)
    {
        $this->Word[] = $definition;
    }

    public function setDefinition_wordBuffer($word, array $definition)
    {
        if (!empty($word))
        {
            $this->WordBuffer[$word] = $definition;
        }
    }

    public function setDefinition_wordBuffer_NI(array $definition)
    {
        $this->WordBuffer[] = $definition;
    }

    public function pushDefinition_wordBuffer($word, $definition)
    {
        if (array_key_exists($word, $this->WordBuffer))
        {
            array_push($this->WordBuffer[$word], $definition);
        } else
        {
            $this->setDefinition_wordBuffer($word, array($definition));
        }
    }

    public function getDefinition_wordBuffer($word)
    {
        if (array_key_exists($word, $this->WordBuffer))
        {
            return $this->WordBuffer[$word];
        }
        return '';
    }

    public function getDefinition_word($word)
    {
        if (array_key_exists($word, $this->Word))
        {
            return $this->Word[$word];
        }
        return '';
    }

    public function getWords()
    {
        return $this->Word;
    }

    public function getWordBuffer()
    {
        return $this->WordBuffer;
    }

    public function setWords(array $words)
    {
        if(count($this->WordBuffer) > 0)
        {
            $this->Word = array_merge($this->Word, $words);
        }
        else 
        {
            $this->Word = $words;           
        }
    }

    public function setWordBuffer(array $wordBuffer)
    {
        if(count($this->WordBuffer) > 0)
        {
            $this->WordBuffer = array_merge($this->WordBuffer, $wordBuffer);
        }
        else
        {
            $this->WordBuffer = $wordBuffer;
        }
    }
    
    public function resetDictionary()
    {
        $this->WordBuffer = array();
        $this->Word = array();
    }
    
    public function resetWordBuffer()
    {
        $this->WordBuffer = array();
    }
    
    public function resetWord()
    {
        $this->Word = array();
    }
    
    public function isUsed()
    {
        if(count($this->WordBuffer) > 0 || count($this->Word) > 0)
        {
            return true;
        }
        return false;
    }
    
    public function printStack_wordBuffer()
    {
        echo '<em>';
        var_dump($this->WordBuffer);
        echo '</em>';
    }

    public function printStack_words()
    {
        echo '<em>';
        var_dump($this->Word);
        echo '</em>';
    }

}
