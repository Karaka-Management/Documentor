<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\CoverageView;
use phpOMS\System\File\Directory;
use phpOMS\Views\ViewAbstract;

class CodeCoverageController
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
        return $this->coverage[$name]['metrics'] ?? null;
    }

    public function getMethod(string $class, string $name)
    {
        return $this->coverage[$class]['function'][$name] ?? null;
    }

    private function parse(string $path)
    {
        $dom = new \DOMDocument;
        $dom->loadXML(file_get_contents($path));
        $files = $dom->getElementsByTagName('file');

        foreach ($files as $file) {
            if(($classElement = $file->getElementsByTagName('class')[0]) !== null) {
                $class = $classElement->getAttribute('namespace') . '\\' . $classElement->getAttribute('name');
                $this->coverage[$class] = [
                    'metrics' => [
                        'complexity' => 0,
                        'methods' => 0,
                        'coveredmethods' => 0,
                    ]
                ];

                if(($metrics = $file->getElementsByTagName('class')[0]->getElementsByTagName('metrics')[0]) !== null) {
                    $this->coverage[$class]['metrics'] = [
                        'complexity' => (int) $metrics->getAttribute('complexity'),
                        'methods' => (int) $metrics->getAttribute('methods'),
                        'coveredmethods' => (int) $metrics->getAttribute('coveredmethods'),
                    ];
                }

                $lines = $file->getElementsByTagName('line');
                foreach($lines as $line) {
                    if($line->getAttribute('type') === 'method') {
                        if(!isset($this->coverage[$class]['function'])) {
                            $this->coverage[$class]['function'] = [];
                        }

                        $this->coverage[$class]['function'][$line->getAttribute('name')] = [
                            'complexity' => (int) $line->getAttribute('complexity'),
                            'crap' => (int) $line->getAttribute('crap'),
                        ];
                    }
                }
            }
        }
    }

    private function countMethods() : int
    {
        $count = 0;
        foreach($this->coverage as $class) {
            if(isset($class['metrics'])) {
                $count += $class['metrics']['methods'];
            }
        }

        return $count;
    }

    private function countCoveredMethods() : int
    {
        $count = 0;
        foreach($this->coverage as $class) {
            if(isset($class['metrics'])) {
                $count += $class['metrics']['coveredmethods'];
            }
        }

        return $count;
    }

    private function countCoveredClasses() : int
    {
        $count = 0;
        foreach($this->coverage as $class) {
            if(isset($class['metrics'])) {
                $count += $class['metrics']['coveredmethods'] === $class['metrics']['methods'] ? 1 : 0;
            }
        }

        return $count;
    }

    private function createBaseFiles() 
    {
        $coverageView = new CoverageView();
        $coverageView->setTemplate('/Documentor/src/Theme/coverage');
        $coverageView->setBase($this->destination);
        $coverageView->setPath($this->destination . '/coverage' . '.html');
        $coverageView->setClasses(count($this->coverage));
        $coverageView->setCoveredClasses($this->countCoveredClasses());
        $coverageView->setMethods($this->countMethods());
        $coverageView->setCoveredMethods($this->countCoveredMethods());

        $this->outputRender($coverageView);
    }

    private function outputRender(ViewAbstract $view)
    {
        Directory::createPath(dirname($view->getPath()), '0644', true);
        file_put_contents($view->getPath(), $view->render());
    }
}