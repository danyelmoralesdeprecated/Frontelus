<?php

namespace Frontelus\Library\Requests;

class RequestSTD
{

    private $Configuration;
    private $Request;
    private $RequestList;
    private $DefaultRequest;
    private $IndexGet;
    private $RquestStackBySymbol;
    private $VarsGetExists;
    
    public function __construct(array $config)
    {
        $this->initialize($config);
    }
    
    private function initialize(array $config)
    {
        $this->Configuration = array_filter($config);
        $this->RequestList = array();
        $this->VarsGetExists = FALSE;
        $this->Request = NULL;
        $this->parseRequest();
    }
    
    private function parseRequest()
    {
        if (!isset($this->Configuration) || empty($this->Configuration))
        {
            die('Something went wrong while trying reading the config file.');
        }
        
        if (!(array_key_exists('variableGet', $this->Configuration) 
           && array_key_exists('defaultRequest', $this->Configuration) ))
        {
            die('Error parsing request: It\'s not defined a request variable.');
        }
        
        if (!$this->parseStructuredRequest())
        {      
            $this->parseConfiguration();
            $this->parseVarsGet();
        }
    }
    
    private function parseStructuredRequest()
    {
        if(isset($_GET['page']))
        {
            $this->Request = '0x1706149192';
            $this->parseVarsGet(array('page', 'section', 'hely')); 
            return true;
        }
        return false;
    }
    
    /**
     * The core of requests
     *
     * @since 0.0.2
     */
    private function parseConfiguration()
    {
        $getIndex = "{$this->Configuration['variableGet']}";
        $defaultRequest = "{$this->Configuration['defaultRequest']}";
        $onVarsGet_do_defaultRequest = TRUE;
        $tmpRequest = NULL;
        
        if (isset($this->Configuration['onVarsGet']['defaultRequest']))
        {
            if ($this->Configuration['onVarsGet']['defaultRequest'])
            {
                $onVarsGet_do_defaultRequest = $this->Configuration['onVarsGet']['defaultRequest'];
            }
            else
            { 
                $onVarsGet_do_defaultRequest = FALSE;
            }
        }
        
        if ($onVarsGet_do_defaultRequest)
        {
            $tmpRequest = (!isset($_GET[$getIndex])) ? $defaultRequest : filter_input(INPUT_GET, $getIndex, FILTER_SANITIZE_URL);
        }
        
        $this->Request = $tmpRequest;
        $this->DefaultRequest = $defaultRequest;
        $this->IndexGet = $getIndex;

        unset($_GET[$getIndex]);
    }
    
    private function parseVarsGet(array $validGets = array())
    {  
        if ($this->Request !== NULL)
        {
            array_push($this->RequestList, $this->Request);
        }
        
        if (array_key_exists('varsGet', $this->Configuration))
        {
            $this->VarsGetExists = TRUE;
            
            if (count($validGets) === 0)
            {
                $validGets = $this->Configuration['varsGet'];
            }
        
            foreach($validGets as $value)
            {
                $tmp = '';
                if(isset($_GET[$value]))
                {
                    $tmp = filter_input(INPUT_GET, $value, FILTER_SANITIZE_URL);
                    unset($_GET[$value]);
                } 
                array_push($this->RequestList, $tmp);
            }
            
        }
    }
    
    /**
     * Emulates a friendly URL. Will Be deprecated.
     *
     * @since 0.0.2
     */
    public function ParseRequestBySymbol($symbol)
    {
        $uriTmp = explode("$symbol", $this->Request);
        $this->RquestStackBySymbol = array_filter($uriTmp);
        $elements = count($this->RquestStackBySymbol);
        return $elements;
    }

    /**
     * Pop a request from the stack of requests.
     *
     * @since 0.0.2
     */
    public function getRequestInStack()
    {
        if (count($this->RquestStackBySymbol) > 0 && is_array($this->RquestStackBySymbol))
        {
            return array_shift($this->RquestStackBySymbol);
        }
        return '';
    }

    /**
     * Returns the main request.
     * Be careful, it's frecuently returned the "defaultRequest" when it's
     * declared the "varsGet" option in config.yml
     * 
     *
     * @since 0.0.2
     */
    public function getPrincipalRequest()
    {
        return filter_var($this->Request, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Returns the "varsGet" list.
     * 
     * @since 0.0.2
     */
    public function getRequests()
    {
        return array_filter($this->RequestList);
    }
    
    /**
     * Returns the "index" from "RequestList"
     * "index" must be especified by the user.
     * 
     * @since 0.0.2
     */
    public function getRequestFromList($index)
    {
        if (in_array($index, $this->RequestList))
        {
            return array_search($index, $this->RequestList);
        }
        return false;
    }
    
    /**
     * If not exists a "Request List" 
     * it returns the "Principal Request".
     * 
     * @since 0.0.2
     */
    public function getRequest()
    {
        if (count($this->RequestList) > 0)
        {
            return $this->getRequests();
        }
        
        return $this->getPrincipalRequest();
    }
    
    /**
     * Returns the name of the default request
     * especified by the user in the config file.
     * 
     * @since 0.0.2
     */
    public function getDefaultRequest()
    {
        return $this->DefaultRequest;
    }

    /**
     * Returns the name of the variable GET
     * especified by the user in the config file.
     * 
     * @since 0.0.2
     */
    public function getIndexGet()
    {
        return $this->IndexGet;
    }

    /**
     * Sets or overrides the principal request using code.
     * 
     * @since 0.0.2
     */
    public function setPrincipalRequest($request)
    {
        $this->Request = filter_var($request, FILTER_SANITIZE_EMAIL);
    }
    
    /**
     * Sets or overrides an index request in RequestList.
     * 
     * @since 0.0.2
     */
    public function setItemInRequestList($index, $value)
    {
        if (count($this->RequestList) > 1)
        {
            if (isset($this->RequestList[$index]))
            {
                $this->RequestList[$index] = $value;
            }
        }
    }
    
    /**
     * Returns False or True if the main request is
     * the only one request executed
     * 
     * @since 0.0.2
     */
    public function isOnlyMainRequest()
    {
        $count = count($this->RequestList);
        if ($count > 1)
        {
            return FALSE;
        }
        elseif($count === 0)
        {
            return NULL;
        }
        else
        {
            return TRUE;
        }
    }
    
    /**
     * Returns the last request in the system.
     * 
     * @since 0.0.2
     */
    public function getLastRequest()
    {
        $count = count($this->RequestList);
        if ($count > 0)
        {
            return $this->RequestList[$count - 1];
        }
        return FALSE;
    }
    
    /**
     * Returns the varsget configuration array.
     * 
     * @since 0.0.2
     */
    public function getOnVarsGet($index = '')
    {
        if(!$this->VarsGetExists)
        {
            return array();
        }
        
        if(isset($this->Configuration['onVarsGet']))
        {
            if(isset($this->Configuration['onVarsGet'][$index]))
            {
                return $this->Configuration['onVarsGet'][$index];
            }
            
            return $this->Configuration['onVarsGet'];
        }
        
    }
    
    /**
     * Restart all the sys configuration.
     * 
     * @since 0.0.2
     */
    public function restart(array $config)
    {
        $this->initialize($config);
    }
    
}
