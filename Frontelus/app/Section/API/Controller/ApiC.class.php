<?php

namespace Controller;

use \Frontelus\Controller\FrontelusController as FController;
use \Frontelus\Library\Universal\StateSaver;

class ApiC extends FController
{

    public function onLoad()
    {
        $this->saver = new StateSaver();
        $this->saver->restore(0, $this->saver->callback_toDefine());
        $this->Model->initialize();
    }

    public function checkFirstTime()
    {
        $data = $this->Model->firstTimeInstalled(1);
        $this->View->setMessage($data);
    }

    public function saveHistory()
    {
        $data = $this->getPayload();
        if ($data !== -1)
        {
            $data = $this->Model->saveHistory($data);
        }
        $this->View->setMessage($data);
    }

    public function saveMarker()
    {
        $data = $this->getPayload();
        if ($data !== -1)
        {
            $data = $this->Model->saveMarker($data);
        }
        $this->View->setMessage($data);
    }

    public function saveWebsite()
    {
        $data = $this->getPayload();
        if ($data !== -1)
        {
            $data = $this->Model->saveWebsite($data);
        }
        $this->View->setMessage($data);
    }

    private function getPayload()
    {
        if (isset($_GET['payload']) && !empty($_GET['payload']))
        {
            $payload = json_decode($_GET['payload']);
            return $payload;
        }

        return -1;
    }

}
