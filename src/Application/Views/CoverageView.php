<?php

namespace Documentor\src\Application\Views;

class CoverageView extends BaseView
{
	public $classes = 1;
	public $coveredClasses = 0;
	public $methods = 1;
	public $coveredMethods = 0;

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
}