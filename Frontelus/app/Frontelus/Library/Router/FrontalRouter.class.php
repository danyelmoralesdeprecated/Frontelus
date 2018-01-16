<?php

namespace Frontelus\Library\Router;

use \Frontelus\Library\Dictionary;
use Frontelus\View\FacadeView;
use Frontelus\Library\Router\FrontalRouterLoader;
use Frontelus\Library\Router\StrucRouter;

class FrontalRouter
{

    private $Pages;
    private $StructuredPage;
    private $View;
    private $Model;
    private $DefaultController;
    private $RouterLoader;
    
    public function __construct(FacadeView $view, $model = NULL, Dictionary $pages = NULL)
    {
        $this->initialize($view, $model, $pages);
    }

    public function initialize(FacadeView $view, $model, Dictionary $pages = NULL)
    {
        if ($model !== NULL)
        {
            if(!is_subclass_of($model, 'Frontelus\\Model\\FrontelusModel'))
            {
                throw new Exception(sprintf(' your Model in %s must be an instance of FrontelusModel', $model));
            }
        }

        $this->Pages = ($pages == NULL) ? new Dictionary() : $pages;
        $this->View = $view;
        $this->Model = $model;
        $this->StructuredPage = new StrucRouter\FrontalStrucRouter($view);
    }
    
    /**
     * add a page action to the system router stack
     *
     * @since 0.0.3
     *
     * @param string     $index  the public request name
     * @param array      $params  The extension   
     */
    public function addPage($index, array $params)
    {
        $error = false;
        
        if (!$index)
        {
            return FALSE;
        }

        if (!isset($params['class']))
        {
            if ($this->DefaultController !== null)
            {
                $params['class'] = $this->DefaultController;
            }
            else
            {
                $error = TRUE;
            }
        }
        
        if (!(isset($params["action"])))
        {
            $error = TRUE;
        }

        if ($error && (!isset($params['strict'])  || $params['strict'] == 'TRUE') )
        {
            return FALSE;
        }
        
        $this->Pages->setDefinition_wordBuffer($index, $params);
        return TRUE;
    }

    public function addFunction($index, $param)
    {
        if (!($index || $param))
        {
            return FALSE;
        }
        $this->StructuredPage->addFunction($index, $param);
    }
    
    /**
     * add a page action to the system router stack
     *
     * @since 0.0.3
     *
     * @param string     $index  the public request name
     * @param array      $params  The extension   
     */
    public function resetPageRegistry()
    {
        $this->Pages->resetDictionary();
    }
    
    public function _routePage($requestObj)
    {
        $request = $requestObj->getRequest();
        if (!is_array($request))
        {
            $this->routePage($request);
            return;
        }
        
        if ($requestObj->getPrincipalRequest() === '0x1706149192')
        {
            $this->StructuredPage->routeStructured($request);
            return;
        }

        $this->routeMultiPage($request);
    }

    public function routeMultiPage(array $request)
    { 
        foreach ($request as $page)
        {
            $this->routePage($page);
        }
    }

    public function routePage($request)
    { 
        $definition = $this->Pages->getDefinition_wordBuffer($request);
        
        if ($definition === '')
        {
            die("Pagina no encontrada...");
        }
        
        if (isset($definition['class']))
        {
            $this->routeController($definition['class'], $definition['action']);
        }
        
        if(isset($definition['view']))
        {
            $vclass = '';
            
            if (isset($definition['vclass']))
            {
                $vclass = $definition['vclass'];
            }
            
            $this->routeView($vclass, $definition['view'], NULL);
        }
    }

    private function routeController($controller, $method, $param = NULL)
    {
        if (!(isset($controller) || $controller))
        {
            die("El controlador no fue encontrado...");
        }

        if (!method_exists($controller, $method))
        {
            die("Accion no encontrada en el controlador.");
        }

        $controllerObj = new $controller($this->View, $this->Model); 
        $response = $controllerObj->$method();
        if ($response) {}
    }
    
    private function routeView($viewClass = NULL, $viewMethod = NULL, $param = NULL)
    {   
        $class = $this->View;
        
        if ($viewClass !== '')
        {
            $class = new $viewClass();
        }
        
        if ($viewMethod !== '')
        {
            if (\Frontelus\R::getGlobalCfg()->searchArray('useJoiner') == TRUE)
            {
               $class->$viewMethod();
            }
            else if (method_exists($class, $viewMethod))
            {
                $class->$viewMethod();
            }
        }
    }

    public function setSinglePage($value, $class)
    {
        $this->Pages->setDefinition_word($value, $class);
    }

    public function setDefaultController($value)
    {
        $this->DefaultController = $value;
    }

    public function loadPageFile($type = 'yml', $file = '')
    {
        $file = ($file !== '') ? 'RouterPage_' . $file : 'RouterPage';
        $this->RouterLoader = new FrontalRouterLoader($this);
        $this->RouterLoader->loadRouterYML($file, $type);
    }
  
}
