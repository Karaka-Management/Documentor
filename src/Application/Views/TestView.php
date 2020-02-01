<?php declare(strict_types=1);

namespace Documentor\src\Application\Views;

class TestView extends BaseView
{
    protected array $test    = [];
    protected array $results = [];

    public function setTest(array $test): void
    {
        $this->test = $test;
    }

    public function setResults(array $results): void
    {
        $this->results = $results;
    }
}
