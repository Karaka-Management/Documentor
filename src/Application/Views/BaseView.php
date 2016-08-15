<?php

namespace Documentor\src\Application\Views;

use phpOMS\Views\ViewAbstract;

class BaseView extends ViewAbstract
{
    private $title = '';

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }
}