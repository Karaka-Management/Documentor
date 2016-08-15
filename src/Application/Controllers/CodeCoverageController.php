<?php

namespace Documentor\src\Application\Controllers;

class CodeCoverageController
{
    private $coverage = [];

    public function getClass(string $name)
    {
        return $this->coverage[$name]['metrics'] ?? null;
    }

    public function getMethod(string $class, string $name)
    {
        return $this->coverage[$class]['function'][$name] ?? null;
    }

    public function parse(string $path)
    {
        $dom = new \DOMDocument;
        $dom->loadXML(file_get_contents($path));
        $files = $dom->getElementsByTagName('file');

        foreach ($files as $file) {
            if($file->getElementsByTagName('class')[0] !== null) {
                $class = str_replace('\\', '/', $file->getAttribute('name'));
                $this->coverage[$class] = [];

                if(($metrics = $file->getElementsByTagName('class')[0]->getElementsByTagName('metrics')[0]) !== null) {
                    $this->coverage[$class]['metrics'] = [
                        'complexity' => $metrics->getAttribute('complexity'),
                        'methods' => $metrics->getAttribute('methods'),
                        'coveredmethods' => $metrics->getAttribute('coveredmethods'),
                    ];
                }

                $lines = $file->getElementsByTagName('line');
                foreach($lines as $line) {
                    if($line->getAttribute('type') === 'method') {
                        $this->coverage[$class]['function'] = [];
                        $this->coverage[$class]['function'][$line->getAttribute('name')] = [
                            'complexity' => $line->getAttribute('complexity'),
                            'crap' => $line->getAttribute('crap'),
                        ];
                    }
                }
            }
        }
    }
}