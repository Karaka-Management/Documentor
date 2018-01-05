<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\TestView;

class UnitTestController
{
    private $destination = '';
    private $base = '';
    private $test = ['tests' => 0, 'assertions' => 0, 'failures' => 0, 'errors' => 0, 'empty' => 0, 'time' => 0];
    private $testResults = ['errors' => [], 'failures' => []];

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
        return '';
    }

    public function getMethod(string $name)
    {
        return '';
    }

    private function parse(string $path)
    {
        $dom = new \DOMDocument();
        $dom->loadXML(file_get_contents($path));
        $root                     = $dom->documentElement->getElementsByTagName('testsuite')[0];
        $this->test['tests']      = $root->getAttribute('tests');
        $this->test['assertions'] = $root->getAttribute('assertions');
        $this->test['failures']   = $root->getAttribute('failures');
        $this->test['errors']     = $root->getAttribute('errors');
        $this->test['empty']      = 0;
        $this->test['time']       = $root->getAttribute('time');

        $failures = $dom->getElementsByTagName('failure');
        foreach ($failures as $failure) {
            $this->testResults['failures'][] = ['class' => $failure->parentNode->getAttribute('class'), 'method' => $failure->parentNode->getAttribute('name')];
        }

        $failures = $dom->getElementsByTagName('error');
        foreach ($failures as $failure) {
            $this->testResults['errors'][] = ['class' => $failure->parentNode->getAttribute('class'), 'method' => $failure->parentNode->getAttribute('name')];
        }

        $suites = $dom->getElementsByTagName('testsuite');
        foreach ($suites as $suite) {
            if ((int) $suite->getAttribute('assertions') === 0) {
                $this->test['empty']++;
            }
        }
    }

    private function createBaseFiles()
    {
        $testView = new TestView();
        $testView->setTemplate('/Documentor/src/Theme/test');
        $testView->setBase($this->base);
        $testView->setPath($this->destination . '/test' . '.html');
        $testView->setTitle('Test');
        $testView->setSection('Test');
        $testView->setTest($this->test);
        $testView->setResults($this->testResults);
        
        file_put_contents($testView->getPath(), $testView->render());
    }
}
