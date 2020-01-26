<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\CoverageView;

class CodeCoverageController
{
    private string $destination = '';
    private string $base        = '';
    
    private array $coverage = [];
    
    private int $totalCrap       = 0;
    private int $totalComplexity = 0;

    public function __construct(string $destination, string $base, string $path = null)
    {
        $this->destination = $destination;
        $this->base        = $base;

        if (isset($path)) {
            $this->parse($path);
        }

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
        $dom = new \DOMDocument();
        $dom->loadXML(\file_get_contents($path));
        $files = $dom->getElementsByTagName('file');

        foreach ($files as $file) {
            if (($classElement = $file->getElementsByTagName('class')[0]) !== null) {
                $class                  = $classElement->getAttribute('namespace') . '\\' . $classElement->getAttribute('name');
                $this->coverage[$class] = [
                    'metrics' => [
                        'complexity'     => 0,
                        'methods'        => 0,
                        'coveredmethods' => 0,
                        'crap'           => 0.0,
                    ],
                ];

                if (($metrics = $file->getElementsByTagName('class')[0]->getElementsByTagName('metrics')[0]) !== null) {
                    $this->coverage[$class]['metrics'] = [
                        'complexity'     => (int) $metrics->getAttribute('complexity'),
                        'methods'        => (int) $metrics->getAttribute('methods'),
                        'coveredmethods' => (int) $metrics->getAttribute('coveredmethods'),
                        'crap'           => 0.0,
                    ];

                    $this->totalComplexity += (int) $metrics->getAttribute('complexity');
                }

                $lines = $file->getElementsByTagName('line');
                foreach ($lines as $line) {
                    if ($line->getAttribute('type') === 'method') {
                        if (!isset($this->coverage[$class]['function'])) {
                            $this->coverage[$class]['function'] = [];
                        }

                        $this->totalCrap                           += (float) $line->getAttribute('crap');
                        $this->coverage[$class]['metrics']['crap'] += (float) $line->getAttribute('crap');

                        $this->coverage[$class]['function'][$line->getAttribute('name')] = [
                            'complexity' => (int) $line->getAttribute('complexity'),
                            'crap'       => (float) $line->getAttribute('crap'),
                        ];
                    }
                }
            }
        }
    }

    private function countMethods() : int
    {
        $count = 0;
        foreach ($this->coverage as $class) {
            if (isset($class['metrics'])) {
                $count += $class['metrics']['methods'];
            }
        }

        return $count;
    }

    private function countCoveredMethods() : int
    {
        $count = 0;
        foreach ($this->coverage as $class) {
            if (isset($class['metrics'])) {
                $count += $class['metrics']['coveredmethods'];
            }
        }

        return $count;
    }

    private function countCoveredClasses() : int
    {
        $count = 0;
        foreach ($this->coverage as $class) {
            if (isset($class['metrics'])) {
                $count += $class['metrics']['coveredmethods'] === $class['metrics']['methods'] ? 1 : 0;
            }
        }

        return $count;
    }

    private static function sortUncovered($a, $b)
    {
        if ($a['uncovered'] == $b['uncovered']) {
            return 0;
        }

        return ($a['uncovered'] < $b['uncovered']) ? 1 : -1;
    }

    private function getTopUncoveredMethods(int $limit) : array
    {
        $uncovered = [];

        return [];
    }

    private function getTopUncoveredClasses(int $limit) : array
    {
        $uncovered = [];
        foreach ($this->coverage as $key => $class) {
            if (\count($uncovered) < $limit) {
                $uncovered[] = ['class' => $key, 'uncovered' => $class['metrics']['methods'] - $class['metrics']['coveredmethods']];
                \usort($uncovered, ['Documentor\src\Application\Controllers\CodeCoverageController', 'sortUncovered']);
            } elseif ($uncovered[$limit - 1]['uncovered'] < $class['metrics']['methods'] - $class['metrics']['coveredmethods']) {
                $uncovered[$limit - 1] = ['class' => $key, 'uncovered' => $class['metrics']['methods'] - $class['metrics']['coveredmethods']];
                \usort($uncovered, ['Documentor\src\Application\Controllers\CodeCoverageController', 'sortUncovered']);
            }
        }

        return $uncovered;
    }

    private static function sortCrap($a, $b)
    {
        if ($a['crap'] == $b['crap']) {
            return 0;
        }

        return ($a['crap'] < $b['crap']) ? 1 : -1;
    }

    private function getTopCrapMethods(int $limit) : array
    {
        $crap = [];
        foreach ($this->coverage as $key => $class) {
            if (isset($class['function'])) {
                foreach ($class['function'] as $method => $crapValue) {
                    if (\count($crap) < $limit) {
                        $crap[] = ['class' => $key, 'method' => $method, 'crap' => $crapValue['crap']];
                        \usort($crap, ['Documentor\src\Application\Controllers\CodeCoverageController', 'sortCrap']);
                    } elseif ($crap[$limit - 1]['crap'] < $crapValue['crap']) {
                        $crap[$limit - 1] = ['class' => $key, 'method' => $method, 'crap' => $crapValue['crap']];
                        \usort($crap, ['Documentor\src\Application\Controllers\CodeCoverageController', 'sortCrap']);
                    }
                }
            }
        }

        return $crap;
    }

    private function getTopCrapClasses(int $limit) : array
    {
        $crap = [];
        foreach ($this->coverage as $key => $class) {
            if (\count($crap) < $limit) {
                $crap[] = ['class' => $key, 'crap' => $class['metrics']['crap']];
                \usort($crap, ['Documentor\src\Application\Controllers\CodeCoverageController', 'sortCrap']);
            } elseif ($crap[$limit - 1]['crap'] < $class['metrics']['crap']) {
                $crap[$limit - 1] = ['class' => $key, 'crap' => $class['metrics']['crap']];
                \usort($crap, ['Documentor\src\Application\Controllers\CodeCoverageController', 'sortCrap']);
            }
        }

        return $crap;
    }

    private function createBaseFiles()
    {
        $coverageView = new CoverageView();
        $coverageView->setTemplate('/Documentor/src/Theme/coverage');
        $coverageView->setBase($this->base);
        $coverageView->setPath($this->destination . '/coverage' . '.html');
        $coverageView->setTitle('Coverage');
        $coverageView->setClasses(count($this->coverage));
        $coverageView->setCoveredClasses($this->countCoveredClasses());
        $coverageView->setMethods($this->countMethods());
        $coverageView->setCoveredMethods($this->countCoveredMethods());
        $coverageView->setSection('Coverage');
        $coverageView->setTopUncoveredMethods($this->getTopUncoveredMethods(15));
        $coverageView->setTopUncoveredClasses($this->getTopUncoveredClasses(5));
        $coverageView->setTopCrapMethods($this->getTopCrapMethods(15));
        $coverageView->setTopCrapClasses($this->getTopCrapClasses(15));
        $coverageView->setComplexity($this->totalComplexity);
        $coverageView->setCrap($this->totalCrap);
        
        \mkdir(\dirname($coverageView->getPath()), 0777, true);
        \file_put_contents($coverageView->getPath(), $coverageView->render());
    }
}
