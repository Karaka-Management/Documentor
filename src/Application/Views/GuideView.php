<?php

namespace Documentor\src\Application\Views;

use Reflection;

class GuideView extends BaseView
{
    protected $nav = [];

    public function setNavigation(array $nav)
    {
        $this->nav = $nav;
    }

    public function getNavigation() 
    {
        $nav = '<ul>';

        foreach($this->nav as $key => $element) {
            if(is_array($element)) {
                
            }
        }

        return $nav . '</ul>'
    }
}