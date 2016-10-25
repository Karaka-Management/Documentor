<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\GuideView;
use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\Local\File;
use phpOMS\Utils\StringUtils;

class GuideController
{
    private $destination = '';
    private $base = '';
    private $nav = [];

    public function __construct(string $destination, string $base, string $path = null)
    {
        $this->destination = $destination;
        $this->base        = $base;

        if (isset($path)) {
            $dir       = new Directory($path);
            $this->nav = $this->createNavigation($dir, $path);
            $this->parse($dir, $path);
        }
    }

    private function createNavigation(Directory $dirs, string $base) : array
    {
        $nav = [];
        foreach ($dirs as $file) {
            if ($file instanceof Directory) {
                $nav[$file->getName()] = $this->createNavigation($file, $base);
            } elseif ($file instanceof File && $file->getExtension() === 'md' && !StringUtils::startsWith($file->getName(), 'README') && !StringUtils::startsWith($file->getName(), 'SUMMARY') && !StringUtils::startsWith($file->getName(), 'index')) {
                $nav[] = ['path' => substr($file->getDirPath(), strlen($base)), 'name' => $file->getName()];
            }
        }

        return $nav;
    }

    private function parse(Directory $dirs, string $base)
    {
        foreach ($dirs as $file) {
            if ($file instanceof Directory) {
                $this->parse($file, $base);
            } elseif ($file instanceof File) {
                if ($file->getExtension() === 'md') {
                    $guideView = new GuideView();
                    $guideView->setTemplate('/Documentor/src/Theme/guide');
                    $guideView->setBase($this->base);
                    $guideView->setPath($this->destination . '/guide/' . substr($file->getDirPath(), strlen($base)) . '/' . $file->getName() . '.html');
                    $guideView->setSection('Guide');
                    $guideView->setTitle('Guide');
                    $guideView->setNavigation($this->nav);
                    $guideView->setContent(file_get_contents($file->getPath()));

                    File::put($guideView->getPath(), $guideView->render());
                } else {
                    File::copy($file->getPath(), $this->destination . '/guide/' . substr($file->getDirPath(), strlen($base)) . '/' . $file->getName());
                }
            }
        }
    }
}