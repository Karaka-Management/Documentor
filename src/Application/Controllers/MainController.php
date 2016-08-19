<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\BaseView;
use phpOMS\System\File\File;

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

        File::put($mainView->getPath(), $mainView->render());
    }
}