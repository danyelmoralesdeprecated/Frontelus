<?php
namespace View;
use Frontelus\View\FacadeView;
use Frontelus\Library\Response\Output;

final class ApiView extends FacadeView
{
    private $output;
    
    public function main()
    { 
        $this->output = new Output();
        
        $this->properties();
    }
    
    public function callBack()
    {
      $pack = $this->getMessage();
  
      if (!is_array($pack))
      {
          $pack = array('stuff' => $this->output->strTo_utf8($pack));
      }
      
      echo json_encode($pack);
    }
    
    private function properties()
    {
    }
}