<?php
namespace View;
class Component
{
    public function __construct()
    {
    }
    
    public function genSocialIcon(array $data)
    {
        $buffer = '';
        
        $socialNetworks = array(
            'facebook' => '//www.facebook.com/'
           ,'twitter' => '//www.twitter.com/'
        );
       
        foreach ($data as $value)
        { 
            if (isset($socialNetworks[$value['socialnetwork_name']]))
            { 
                $buffer .= '<a class="' . $value['socialnetwork_name'] . '" href="' . $socialNetworks[$value['socialnetwork_name']] . '/'. $value['social_username'] .'"><i class="fa fa-' . $value['socialnetwork_name'] . '"></i></a>';
            }
        }   

        return $buffer;
    }
}