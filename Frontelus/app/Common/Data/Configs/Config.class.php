<?php

namespace Data\Configs;

defined('_EXEC') or die;

class Config
{

    //General settings system
    public static $general = array(
        'lang_default' => 'en',
        'compressHtml' => false,
        'urlFriendly' => true
    );
    //Database
    public static $database = array(
        'type' => 'mysql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'db_trackflag',
        'port' => '',
        'prefix' => 't1706_',
        'prefixView' => 'vh1706y_'
    );
    //Mailing
    public static $mail = array(
        'auth' => true,
        'host' => 'trackflag.com',
        'user' => 'noreply@trackflag.com',
        'password' => '',
        'secure' => 'tls',
        'port' => 25
    );
   
}
