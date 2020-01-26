<?php

namespace Documentor\src\Application\Views;

class TestView extends BaseView
{
    protected array $test    = [];
    protected array $results = [];

    public function setTest(array $test)
    {
        $this->test = $test;
    }

    public function setResults(array $results)
    {
        $this->results = $results;
    }
}
