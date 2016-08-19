<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\BaseView;
use phpOMS\System\File\Directory;
use phpOMS\Views\ViewAbstract;

class MainController
{
    private $destination = '';

    public function __construct(string $destination)
    {
        $this->destination = $destination; 
        $this->createBaseFiles();
    }

    private function createBaseFiles() 
    {
        $mainView = new BaseView();
        $mainView->setTemplate('/Documentor/src/Theme/index');
        $mainView->setBase($this->destination);
        $mainView->setPath($this->destination . '/index' . '.html');
        $mainView->setSection('Main');
        $mainView->setTitle('Main');

        $this->outputRender($mainView);
    }

    private function outputRender(ViewAbstract $view)
    {
        Directory::create(dirname($view->getPath()), '0644', true);
        file_put_contents($view->getPath(), $view->render());
    }
}