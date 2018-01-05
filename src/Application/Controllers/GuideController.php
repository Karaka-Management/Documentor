<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\GuideView;

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
            $dir       = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
            $this->nav = $this->createNavigation($dir, $path);
            $this->parse($dir, $path);
        }
    }

    private function createNavigation(RecursiveIteratorIterator $dirs, string $base) : array
    {
        $nav = [];
        foreach ($dirs as $file) {
            if ($file->isDir()) {
                $nav[$file->getFilename()] = $this->createNavigation($file, $base);
            } elseif ($file->isFile() $file->getExtension() === 'md' && substr($file->getFilename(), 0, strlen('README')) !== 'README' && substr($file->getFilename(), 0, strlen('SUMMARY')) !== 'SUMMARY' && substr($file->getFilename(), 0, strlen('index')) !== 'index') {
                $nav[] = ['path' => substr($file->getPath(), strlen($base)), 'name' => $file->getFilename()];
            }
        }

        return $nav;
    }

    private function parse(RecursiveIteratorIterator $dirs, string $base)
    {
        foreach ($dirs as $file) {
            if ($file->isDir()) {
                $this->parse($file, $base);
            } elseif ($file->isFile()) {
                if ($file->getExtension() === 'md') {
                    $guideView = new GuideView();
                    $guideView->setTemplate('/Documentor/src/Theme/guide');
                    $guideView->setBase($this->base);
                    $guideView->setPath($this->destination . '/guide/' . substr($file->getPath(), strlen($base)) . '/' . $file->getFilename() . '.html');
                    $guideView->setSection('Guide');
                    $guideView->setTitle('Guide');
                    $guideView->setNavigation($this->nav);
                    $guideView->setContent(file_get_contents($file->getPathname()));

                    file_put_contents($guideView->getPath(), $guideView->render());
                } else {
                    copy($file->Pathname(), $this->destination . '/guide/' . substr($file->getPath(), strlen($base)) . '/' . $file->getFilename());
                }
            }
        }
    }
}
