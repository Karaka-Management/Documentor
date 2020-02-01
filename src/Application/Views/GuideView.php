<?php declare(strict_types=1);

namespace Documentor\src\Application\Views;

class GuideView extends BaseView
{
    protected array $nav = [];

    protected string $content = '';

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setNavigation(array $nav): void
    {
        $this->nav = $nav;
    }

    public function getNavigation() : string
    {
        return $this->generateNavigation($this->nav);
    }

    private function generateNavigation(array $nav) : string
    {
        $navString = '<ul>';

        foreach ($nav as $key => $element) {
            if (!isset($element['name'])) {
                $navString .= '<li><a href="">' . $key . '</a>' . $this->generateNavigation($element);
            } else {
                $navString .= '<li><a href="' . $this->base . '/guide' . $element['path'] . '/' . $element['name'] . '.html">' . \str_replace('_', ' ', $element['name']) . '</a>';
            }
        }

        return $navString . '</ul>';
    }
}
