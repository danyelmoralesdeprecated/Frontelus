<?php
namespace Model;

use Frontelus\Model\FrontelusModel;
use Data\Configs\Config;
use Frontelus\Library\Thirdparty\DB\Medoo\Medoo;
#use Frontelus\Library\Thirdparty\DB\ESQLML\Facade as ESQLML;

class Trackflag extends FrontelusModel
{
    private $DBConnector;
    
    public function __construct()
    {
        parent::__construct();
        $this->initialize();
    }
    
    public function initialize()
    { 
        $array = array(
            'database_type' => Config::$database['type'],
            'database_name' => Config::$database['database'],
            'server' => Config::$database['host'],
            'username' => Config::$database['user'],
            'password' => Config::$database['password'],
            'charset' => 'utf8'
        );
        $this->DBConnector = new Medoo($array);
    }
    
    public function CheckLogin($email, $password)
    {
        $table = Config::$database['prefixView'] . 'user';

        //select the email  
        $user = $this->DBConnector->select($table, array('user_password'), array('user_email[=]' => $email));

        if (count($user) != 1)
        {
            return FALSE;
        }

        if ($user[0]['user_password'] !== $password)
        {
            return FALSE;
        }

        $dataUser = $this->DBConnector->select($table, '*', array('AND'=>array('user_password[=]' => $password, 'user_email[=]' => $email)));
        if (count($dataUser) != 1)
        {
            return FALSE;
        }

        $_SESSION['user_flag_logged'] = $dataUser;
        return TRUE;
    }
    
}
