<?php

namespace Documentor\src\Application\Views;

class BaseView
{
    protected $title = '';
    protected $base = '';
    protected $path = '';
    protected $section = '';
    protected $template = '';
    
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }
    
    public function render(...$data)
    {
        $ob   = '';
        $path = __DIR__ . '/../..' . $this->template . '.tpl.php';
        
        if (!file_exists($path)) {
            throw new Exception($path);
        }
        
        try {
            ob_start();
            /** @noinspection PhpIncludeInspection */
            $includeData = include $path;
            
            $ob = ob_get_clean();
            
            if (is_array($includeData)) {
                return $includeData;
            }
            
        } catch(\Throwable $e) {
            $ob = '';
        } finally {
            return $ob;
        }
    }

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
