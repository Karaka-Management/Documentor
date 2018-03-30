<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\BaseView;

class MainController
{
    private $destination = '';
    private $base        = '';

    public function __construct(string $destination, string $base)
    {
        $this->destination = $destination;
        $this->base        = $base;
        $this->createBaseFiles();
    }

    private function createBaseFiles()
    {
        $mainView = new BaseView();
        $mainView->setTemplate('/Documentor/src/Theme/index');
        $mainView->setBase($this->base);
        $mainView->setPath($this->destination . '/index' . '.html');
        $mainView->setSection('Main');
        $mainView->setTitle('Main');

        mkdir(dirname($mainView->getPath()), 0777, true);
        file_put_contents($mainView->getPath(), $mainView->render());
    }
}
