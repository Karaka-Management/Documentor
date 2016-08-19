<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Views\TestView;
use phpOMS\System\File\Directory;
use phpOMS\Views\ViewAbstract;


class UnitTestController
{
    private $destination = '';
    private $test = [];
    private $testResults = ['errors' => [], 'failures' => []];

    public function __construct(string $destination, string $path = null)
    {
        $this->destination = $destination;

        if(isset($path)) {
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
        $testView->setBase($this->destination);
        $testView->setPath($this->destination . '/test' . '.html');
        $testView->setTitle('Test');
        $testView->setSection('Test');
        $testView->setTest($this->test);
        $testView->setResults($this->testResults);

        $this->outputRender($testView);
    }

    private function outputRender(ViewAbstract $view)
    {
        Directory::create(dirname($view->getPath()), '0644', true);
        file_put_contents($view->getPath(), $view->render());
    }
}