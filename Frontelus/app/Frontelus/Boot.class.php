<?php
namespace Frontelus;
class Boot
{
    private static $UserIgnoredClasses = array();

    /**
     * Sets the sys configuration attributes.
     *
     * @since 0.0.2
     */
    public static function run($cfg, $site = '')
    {
        define('HelyFlag', 1);
        define('_EXEC', 1);
        self::init($site);
        self::loadLoader(); 
        self::loadCfg($cfg, $site); 
        self::loadFXFile(); 
    }

    public static function setUserIgnoredClasses(array $array)
    {
        self::$UserIgnoredClasses = $array;
    }
    
    /**
     * Initialize the most important features in frontelus
     *
     * @since 0.0.2
     */
    private static function init($site = '')
    {
        R::initialize($site);
        #self::setErrReport(0);
    }

    private static function setIndexPath($index = '')
    {
        R::setIndexPath($index);
    }
    
    private static function loadCfg($cfg)
    { 
        R::loadCfg($cfg);
    }
    
    private static function loadLoader()
    {
        require_once __DIR__ . '/Library/Autoloader' . R::$AutoLoaderExtFile;
        \Frontelus\Library\Autoloader::setIgnoringList(self::getIgnoringList());
        spl_autoload_register("Frontelus\Library\Autoloader::parseClass");
    }
    
    public static function unLoadLoader()
    {
        spl_autoload_unregister("Frontelus\Library\Autoloader::parseClass");
    }
    
    private static function getIgnoringList()
    {
        $ignoredClasses = array_merge(  array( 'R' . R::$AutoLoaderExtFile
                                            , 'Boot' . R::$AutoLoaderExtFile)
                                       ,self::$UserIgnoredClasses);
        return $ignoredClasses;
    }
    
    private static function loadFXFile()
    {
        $fxFile = R::$APP_PATH 
                . R::$DS 
                . R::$Microsite
                . R::getSysCfg('fxFile');
        
        if (file_exists($fxFile))
        {
            include_once ($fxFile);
        }
    }
    
    /**
     * Sets the error level allowed to be reported 
     *
     * @since 0.0.2
     *
     * @param integer     $int The value of error level
     */
    public static function setErrReport($int)
    {
        error_reporting($int);
    }
}

class R
{
    public static $DS;
    public static $FR_PATH;
    public static $APP_PATH;
    public static $Microsite;
    public static $AutoLoaderExtFile;
    public static $indexPath;
    public static $MicrositePath;
    private static $R;
    public static $SESSION;
    
    public function __construct()
    {
    }
    
    public static function loadCfg($cfg)
    {
        self::setContext(new \Frontelus\Config\ConfigHelper($cfg));
        self::$SESSION = new \Frontelus\Library\Security\Session();
    }
    
    public static function initialize($site)
    {
       self::$FR_PATH = __DIR__;
       self::$DS = DIRECTORY_SEPARATOR;
       self::$APP_PATH = dirname(__DIR__);
       self::$AutoLoaderExtFile = '.class.php';  
       self::$Microsite = ($site !== '')? 'Section' . R::$DS . $site . R::$DS : '';
       self::$MicrositePath = R::$APP_PATH . R::$DS . dirname(R::$Microsite);
    }
    
    /**
     * Set the frotnelus configuration object 
     *
     * @since 0.0.2
     */
    public static function setContext($context)
    {
        self::$R = $context;
    }
    
    /**
     *              IndexPath
     * ------------------------------------------
     * The path to find your web/index.php.
     * 
     * This is used when you have to use more
     * than 1 microsite and you need to share
     * some resources like JS, CSS, etc.
     *
     * @since 0.0.2
     */
    public static function setIndexPath($index = '')
    {
        self::$indexPath = $index;
    }
    
    public static function getIndexPath()
    { 
        if (!isset(self::$indexPath))
        {
            $indexSlashTmp = self::$R->getGlobalConfiguration()
                                        ->searchArray('indexPath');
            self::$indexPath = (is_array($indexSlashTmp) )?'':$indexSlashTmp;
        }
        return self::$indexPath;
    }
    
    /**
     * Get the default sys configuration variables
     *
     * @since 0.0.2
     */
    public static function getSysCfg($index)
    {
        return self::$R->getSysCfg($index);
    }
    
    /**
     * Returns a SysO index.
     * 
     * The SysO is an object container needed by frontelus.
     *
     * @since 0.0.2
     */
    public static function getSysO($index)
    {
        return self::$R->getSysO($index);
    }
    
    /**
     * Returns the configuration file loaded
     *
     * @since 0.0.2
     */
    public static function getGlobalCfg()
    {
        return self::$R->getGlobalConfiguration();
    }
    
    /**
     * Set the principal Dir View of the system
     *
     * @since 0.0.2
     */
    public static function setViewDirName($dir)
    {
        if(!is_object($dir))
        {
           die();
        }
        
        $objInfo = new \ReflectionClass($dir);
        $dir = dirname($objInfo->getFileName());
        
        if(self::getGlobalCfg()->searchArray('useJoiner') == TRUE)
        { 
           \Frontelus\Library\Joiner::setObj($dir);
        }
        
        $baseName = basename($dir);
        self::$R->setSysCfg('ViewDir', $baseName);
    }

    public static function activateI18N($sys, $lang = 'default', $helper = FALSE)
    {
        if (!is_object($helper))
        {
            $helper = new \Frontelus\Library\I18N\I18NHelper($sys, $lang);
        }
        
        self::$R->setSysO('I18N', $helper);
    }
    
}
