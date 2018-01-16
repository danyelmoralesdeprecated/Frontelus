<?php

namespace Frontelus\Library\I18N;

interface InterfaceI18N
{
    public function loadLang($domain, $lang);
    public function translate($value, $type); 
    public function getTranslations($type, array $strids); 
    public function getErrorId(); 
    public function getErrorMessage($id); 
}
