<?php

namespace Documentor\src\Application\Views;

use phpOMS\Views\ViewAbstract;

class BaseView extends ViewAbstract
{
    protected $title = '';
    protected $base = '';
    protected $path = '';
    protected $section = '';

    public function getSection()
    {
        return $this->section;
    }

    public function setSection(string $section)
    {
        $this->section = $section;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setBase(string $path)
    {
        $this->base = str_replace('\\', '/', $path);
    }

    public function getPath() : string
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }
}