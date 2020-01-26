<?php

namespace Documentor\src\Application\Views;

class CoverageView extends BaseView
{
    protected int $classes        = 0;
    protected int $coveredClasses = 0;
    protected int $methods        = 0;
    protected int $coveredMethods = 0;
    protected int $complexity     = 0;
    
    protected float $crap = 0.0;
    
    protected array $uncoveredClasses = [];
    protected array $uncoveredMethods = [];
    protected array $crapClasses      = [];
    protected array $crapMethods      = [];

    public function setClasses(int $amount)
    {
        $this->classes = $amount > 0 ? $amount : 1;
    }

    public function setCoveredClasses(int $amount)
    {
        $this->coveredClasses = $amount;
    }

    public function setMethods(int $amount)
    {
        $this->methods = $amount > 0 ? $amount : 1;
    }

    public function setCoveredMethods(int $amount)
    {
        $this->coveredMethods = $amount;
    }

    public function setComplexity(int $complexity)
    {
        $this->complexity = $complexity;
    }

    public function setCrap(float $crap)
    {
        $this->crap = $crap;
    }

    public function setTopUncoveredMethods(array $methods)
    {
        $this->uncoveredMethods = $methods;
    }

    public function setTopUncoveredClasses(array $classes)
    {
        $this->uncoveredClasses = $classes;
    }

    public function setTopCrapMethods(array $methods)
    {
        $this->crapMethods = $methods;
    }

    public function setTopCrapClasses(array $classes)
    {
        $this->crapClasses = $classes;
    }
}
