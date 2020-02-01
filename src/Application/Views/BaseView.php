<?php declare(strict_types=1);

namespace Documentor\src\Application\Views;

class BaseView
{
    protected string $title    = '';
    protected string $base     = '';
    protected string $path     = '';
    protected string $section  = '';
    protected string $template = '';

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function render(...$data)
    {
        $ob   = '';
        $path = __DIR__ . '/../../../..' . $this->template . '.tpl.php';

        if (!\file_exists($path)) {
            throw new \Exception($path);
        }

        try {
            \ob_start();
            /** @noinspection PhpIncludeInspection */
            $includeData = include $path;

            $ob = \ob_get_clean();

            if (\is_array($includeData)) {
                return $includeData;
            }
        } catch(\Throwable $e) {
            echo $e->getMessage();
        } finally {
            return $ob;
        }
    }

    public function getSection()
    {
        return $this->section;
    }

    public function setSection(string $section): void
    {
        $this->section = $section;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setBase(string $path): void
    {
        $this->base = \str_replace('\\', '/', $path);
    }

    public function getPath() : string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
