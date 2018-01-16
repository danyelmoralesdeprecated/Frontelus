<?php
namespace Frontelus\Library\Router;
use Frontelus\Library\Dictionary;
abstract class FrontalCategoryRouter extends FrontalRouter implements IFrontal
{
    protected $Request;
    protected $Category;
    
    public function __construct($request, $view)
    {
        parent::__construct($view);
        $this->Request = $request;
        $this->Category = new Dictionary();
        $this->init();
        $this->_route();
    }
    
    public function addCategory($index, $params)
    {
        $this->Category->setDefinition_word($index, $params);
    }

    private function routeCategory($request)
    {
        $category = $this->Category->getDefinition_word($request);
        if(!$category)
        {
            echo "Alias de categoria no encontrada...";
            exit;
        }

        if (!(isset($category) || $category))
        {
            echo "Categoria no registrada...";
            exit;
        }
        
        if (!method_exists($this, $category))
        {
            echo "Accion no encontrada en las categorias.";
            exit;
        }
        
        $this->$category();
        return TRUE;
    }
    
    private function _route()
    {
        $page = "";
        $category = "";
        
        if(!$this->Request)
        {
            return FALSE;
        } 
       
        if($this->Request->ParseRequestBySymbol('/') > 1)
        {
            $category = $this->Request->getRequestInStack();
            $page = $this->Request->getRequestInStack();
        }else{
            $page = $this->Request->getRequest();
            $category = "default";
        }
        
        $this->routeCategory($category);
        $this->routePage($page);
        return TRUE;
    }
   
}
