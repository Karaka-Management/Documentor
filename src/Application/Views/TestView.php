<?php

namespace Documentor\src\Application\Views;

class TestView extends BaseView
{
    protected $test = [];
    protected $results = [];

    public function setTest(array $test)
    {
        $this->test = $test;
    }

    public function setResults(array $results)
    {
        $this->results = $results;
    }
}