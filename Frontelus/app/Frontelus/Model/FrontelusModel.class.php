<?php

namespace Frontelus\Model;

use Frontelus\R;

class FrontelusModel
{

    protected $Message;
    protected $Messenger;

    public function __construct()
    {
        $this->Messenger = R::getSysO('Messenger');
    }

    public function getMessage()
    {
        if ($this->Message === null)
        {
            return '';
        }

        return $this->Message;
    }

    public function setMessage($value)
    {
        $this->Message = $value;
    }
       
}
