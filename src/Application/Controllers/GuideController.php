<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\BaseView;
use phpOMS\System\File\Directory;
use phpOMS\Views\ViewAbstract;

class GuideController
{
    private $destination = '';
    private $nav = [];

    public function __construct(string $destination, string $path = null)
    {
        $this->destination = $destination; 

        if(isset($path)) {
            $this->parse(new Directory($path), $path);
        }
    }

    private function parse(Directory $dirs, string $base) 
    {
        foreach($dirs as $file) {
            if($file instanceof Directory) {
                $this->nav[$file->getName()] = [];
                $this->parse($file, $base);
            } elseif($file instanceof File) {
                $this->nav[$file->getDirName()] = ['path' => substr($file->getDirPath(), strlen($base)), 'name' => $file->getName()];

                $guideView = new BaseView();
                $guideView->setTemplate('/Documentor/src/Theme/guide');
                $guideView->setBase($this->destination);
                $guideView->setPath($this->destination . substr($file->getDirPath(), strlen($base)) . '/index' . '.html');
                $guideView->setSection('Guide');
                $guideView->setTitle('Guide');

                $this->outputRender($guideView);
            }
        }
    }

    private function outputRender(ViewAbstract $view)
    {
        Directory::create(dirname($view->getPath()), '0644', true);
        file_put_contents($view->getPath(), $view->render());
    }
}