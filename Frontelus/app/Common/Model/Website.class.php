<?php

namespace Model;

use Model\BaseModel;
use Model\User;

class Website extends BaseModel
{
    CONST UNKNOWN = 0;
    CONST INVALID = 503;
    CONST VALID = 200;
    CONST NOT_FOUND = 404;
    
    private $id;
    private $title;
    private $url;
    private $description;
    private $screenshot;
    private $domain;
    private $visibility;
    private $recommendation;
    private $status;
    private $owner;
    
    public function __construct($id = 0)
    {
        parent::__construct();
        $this->initializeById($id);
    }

    private function initializeById($id)
    {
        if ($id === 0)
        {
            return FALSE;
        }
    }

    public function initializeByMeta($meta)
    {
        // se pasan meta array para init
    }

    public function initializeByUrl($url)
    {
        // aquí se ejecuta el crawler y nos debe retornar un array para init
    }

    private function setProperty(array  $property)
    {
        
    }
    
    public function save()
    {
        
    }

    public function delete()
    {
        
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setScreenshot($screenshot)
    {
        $this->screenshot = $screenshot;
    }

    public function setFlag($flag)
    {
        
    }

    public function setRecommendation($recommendation)
    {
        $this->recommendation = $recommendation;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setOwner(User $user)
    {
        $this->owner = $user;
    }
    
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getRecommendation()
    {
        return $this->recommendation;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getVisibility()
    {
        return $this->visibility;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getScreenshot()
    {
        return $this->screenshot;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getContainers()
    {
        #return $this->DBConnector->select($this->userTable_DB, '*', array('user_id[=]' => $this->owner->getID()));
    }
    
}
