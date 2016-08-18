<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\TestView;
use phpOMS\System\File\Directory;
use phpOMS\Views\ViewAbstract;


class UnitTestController
{
    private $destination = '';
    private $coverage = [];

    public function __construct(string $destination, string $path)
    {
        $this->destination = $destination; 

        $this->parse($path);
        $this->createBaseFiles();
    }

    public function getClass(string $name)
    {
        return '';
    }

    public function getMethod(string $name)
    {
        return '';
    }

    private function parse(string $path) 
    {

    }

    private function createBaseFiles() 
    {
        $testView = new TestView();
        $testView->setTemplate('/Documentor/src/Theme/test');
        $testView->setBase($this->destination);
        $testView->setPath($this->destination . '/test' . '.html');
        $testView->setSection('Test');

        $this->outputRender($testView);
    }

    private function outputRender(ViewAbstract $view)
    {
        Directory::createPath(dirname($view->getPath()), '0644', true);
        file_put_contents($view->getPath(), $view->render());
    }
}