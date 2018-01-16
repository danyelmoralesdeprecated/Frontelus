<?php
namespace View;

use Frontelus\View\FacadeView;
use View\Component;

final class UserView extends FacadeView
{
    private $component;
    
    public function main()
    { 
        $this->component = new Component();
        $this->setDefaultLayout('base.php');
        $this->addFile('Content.Html.header', 'Content/Html/header.php');
    }
    
    public function showProfileBase()
    {
        $this->addFile('Content.Html.content', 'Content/Html/section_profile.php');
        $message = $this->getMessage();

        # filling the user numbers
        $this->addFileContent('Content.Text.numbers.recommendations', $message['info']['Recommendations']);
        $this->addFileContent('Content.Text.numbers.followers', $message['info']['Followers']);
        $this->addFileContent('Content.Text.numbers.following', $message['info']['Following']);
        
        # filling the user data
        $this->addFileContent('Content.Text.info.fullname', $message['info']['user_name'] . ' ' .$message['info']['user_lastName']);
        $this->addFileContent('Content.Text.info.username', '@' . $message['info']['user_userName']);
        $this->addFileContent('Content.Text.info.imgProfile', $message['info']['user_image']);
        
        # filling the social network icons
        $icons = $this->component->genSocialIcon($message['info']['socialNetwork']);
        $this->addFileContent('Content.Html.info.socialNetwork', $icons);
    }
    
}