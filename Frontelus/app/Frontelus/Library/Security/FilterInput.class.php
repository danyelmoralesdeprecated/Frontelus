<?php

namespace Frontelus\Library\Security;

class FilterInput
{

    public function filterGet($index, $constant, $reset = false)
    {
        $data = filter_input(INPUT_GET, $index, $constant);
        if (!$reset)
        {
            return $data;
        }
        $_GET[$index] = $data;
    }

    public function filterPost($index, $constant, $reset = false)
    {
        $data = filter_input(INPUT_POST, $index, $constant);
        if (!$reset)
        {
            return $data;
        }
        $_POST[$index] = $data;
    }

    public function encodeEntryPost($input, $allowedTags, $flag,  $reset = false)
    {
        $value = $this->encodeEntry($_POST[$input], $allowedTags, $flag, $reset);
        if (!$reset)
        {
            return $value;
        }
    }

    public function encodeEntry(&$input, $allowedTags, $flag,  $reset = false)
    {
        $value = $this->sanitize_output($input);
        $strT = trim($value, " \t\n\r\0\x0B");
        $str = strip_tags($strT, $allowedTags);
        $entity = htmlspecialchars($str, $flag);
        $value = trim($entity, " \t\n\r\0\x0B");
        
        if (!$reset)
        {
            return $value;
        }
        
        $input = $value;
    }
    
    public function decodeEntry($input, $flag)
    {
        return htmlspecialchars_decode($input, $flag);
    }

    public function sanitize_output($buffer)
    {

        $search = array(
            '/\>[^\S ]+/s', // strip whitespaces after tags, except space
            '/[^\S ]+\</s', // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );

        $replace = array(
            '>',
            '<',
            '\\1'
        );

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }

    public function filterEmptyGlobalVar(array $vars, $type)
    {
        $arr = array();
        
        if ($type === 'get')
        {
           $arr = $_GET;
        }
        elseif($type === 'post')
        {
            $arr = $_POST;
        }
        else
        {
            return false;
        }
        
        foreach($vars as $key => $value)
        {
            if((!isset($arr[$value]) || empty($arr[$value])) && $key !== 'E') 
            {
                return false;
            }
        }
        return true;
    }
    
    public function getValsFromGlobalVar(array $vars, $type)
    {
        $arr = array();
        $data = array();
        
        if ($type === 'get')
        {
           $arr = $_GET;
        }
        elseif($type === 'post')
        {
            $arr = $_POST;
        }
        else
        {
            return false;
        }
        
        foreach($vars as $value)
        {
           $data[] = $arr[$value];
        }
        
        return $data;
    }
    
}
