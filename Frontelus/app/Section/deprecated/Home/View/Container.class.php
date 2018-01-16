<?php
namespace View;
use Frontelus\View\FacadeView;
final class Container extends FacadeView
{
    
    public function main()
    {
        $this->setDefaultLayout('index.html');
    }
    
    public function displayHelloBunny()
    {
    }
}