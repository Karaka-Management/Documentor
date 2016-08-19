<?php

namespace Documentor\src\Application\Views;

use Reflection;

class GuideView extends BaseView
{
    protected $nav = [];
    protected $content = '';

    public function setContent(string $content) 
    {
        $this->content = $content;
    }

    public function setNavigation(array $nav)
    {
        $this->nav = $nav;
    }

    public function getNavigation(array $nav) 
    {
        return $this->generateNavigation($this->nav);
    }

    private function generateNavigation(array $nav) : array 
    {
        $nav = '<ul>';

        foreach($this->nav as $key => $element) {
            if(is_array($element)) {
                $nav .= '<li>' . $this->getNavigation();
            } else {
                $nav .= '<li>' . $element;
            }
        }

        return $nav . '</ul>'
    }
}