<?php

namespace Frontelus\View;

use Frontelus\View\FrontelusLayout;
use Frontelus\View\ResourceLoader;
use Frontelus\Library\Collection;

class FrontelusView
{
    private $Layout;
    private $Tools;
    private $Resource;
    private $workingSystem;
    private $I18N;
    
    public function __construct(FrontelusLayout $layout)
    {
        $this->Layout = $layout;
        $this->Tools = new Collection();
        $this->Resource = new ResourceLoader();
        $this->workingSystem = true;
        $this->I18N = FALSE;
    }

    /**
     * =========================================================================
     * @desc loads the base used in the site renderization
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required FrontelusLayout memory location
     * @required Frontelus Tool collection
     * @return string $templateLayout 
     */
    public function showView()
    {
        if ($this->Tools->existAjaxRequest()): $this->turnOff(); endif;
        if (!$this->workingSystem):  return; endif;
        if ($this->Layout->getLayout() === ''): return false; endif;
        return $this->fillContentBuffer();
    }

    /**
     * =========================================================================
     * @desc loads the base used in the site renderization
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required FrontelusLayout memory location
     * @required Frontelus Tool collection
     * @return string $templateLayout 
     */
    private function loadLayout($layout)
    { 
        $templateLayout = '';
        $dirLayout = $this->Layout->getDirLayout();
        $layoutPath = $this->Tools->generatePathView($dirLayout, $layout);
        if($layoutPath !== '')
        { 
            $templateLayout = $this->Tools->getContentByType($layoutPath); 
        } 
        return $templateLayout;
    }

    /**
     * =========================================================================
     * @desc render existing stacks by priority
     * examples include fillContentBuffer();
     * @author Daniel Morales danyelmorales1991@gmail.com
     * @required FrontelusLayout memory location
     * @return string $data 
     */
    private function fillContentBuffer()
    {
        $data = $this->loadLayout($this->Layout->getLayout());

        if ($this->Layout->getContentFlag() > 0)
        {
            $layoutContent = $this->Layout->getContent();
            $data = $this->render($data, $layoutContent);
        }

        if ($this->Layout->getFileFlag() > 0)
        {
            $layoutContent = $this->Layout->getFile();
            $data = $this->render($data, $layoutContent, true);
        }

        if ($this->Layout->getFileContentFlag() > 0)
        {
            $layoutContent = $this->Layout->getContentFile(); 
            $data = $this->render($data, $layoutContent);
        }
        
        if ($this->Layout->getHtmlResourcesFlag() > 0)
        {
            $resourcesObj = $this->Layout->getHtmlResources();
            $layoutContent = $resourcesObj->getArrayResStringify(true);
            $data = $this->render($data, $layoutContent);
        }

        if ($this->Layout->getCfgFlag() > 0)
        {
            $layoutContent = $this->Layout->getCfg();
            $data = $this->render($data, $layoutContent);
        }
        
        return $data;
    }

    /**
     * =========================================================================
     * @desc get a layout and replace a placeholder with some stuff.
     * @param  string  $layout  replacing base
     * @param  array  $content  information from stack
     * @author  Daniel Morales  danyelmorales1991@gmail.com
     * @required  FrontelusLayout  memory location
     * @return  string  $data 
     */
    private function render($layout, array $content, $isFile = false)
    {
        $templateLayout = $layout;
        foreach ($content as $key => $value)
        {
            if ($isFile)
            {
                $value = $this->loadLayout($value);
            }

            $templateLayout = str_replace('%{' . $key . '}%', $value, $templateLayout);
        }
        return $templateLayout;
    }

    /**
     * =========================================================================
     * @desc send the view's code of layout to user's navigator
     * @author  Daniel Morales  danyelmorales1991@gmail.com
     * @return  void 
     */
    public function flushView($data)
    {
        if ($this->I18N)
        {
            $data = $this->I18N->translate($data);      
        }
        echo $data;
    }
    
    public function turnOnI18N($I18N)
    {
        if ($I18N !== '')
        {
            $this->I18N = $I18N;
        }
    }
    
    public function turnOffI18N()
    {
        $this->I18N = FALSE;
    }
    
    public function turnOff()
    {
         $this->workingSystem = false;
    }
    
    public function turnOn()
    {
         $this->workingSystem = true;
    }
    
    public function parseArray($data, array $array, $concat = '')
    {
        $buffer = $data;
        foreach($array as $key => $value)
        { 
             $buffer = str_replace('%{' . $concat.$key . '}%', $value, $buffer);
        }
        return $buffer;
    }
    
    public function getLayoutContent($layout)
    {
        return $this->loadLayout($layout);
    }
    
}
