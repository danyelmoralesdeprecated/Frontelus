<?php
namespace View;
use Frontelus\View\FacadeView;
final class Trackflag extends FacadeView
{
    public function main()
    { 
        $this->properties();
        $this->setDefaultLayout('base.php');
        $this->addFile('Content.Html.header', 'Content/Html/header.php');
    }
    
    public function showLandingPage()
    {
        $this->addFile('Content.Html.content', 'Content/Html/landingpage.php');
    }
    
    public function showLoginForm()
    {
        $this->addFile('Content.Html.content', 'Content/Html/login.php');
    }
    
    private function properties()
    {
        $this->addContent('Content.Html.title', 'Trackflag');
    }
}