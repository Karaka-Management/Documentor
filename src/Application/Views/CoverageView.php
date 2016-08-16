<?php

namespace Documentor\src\Application\Views;

class CoverageView extends BaseView
{
	private $classes = 0;
	private $coveredClasses = 0;
	private $methods = 0;
	private $coveredMethods = 0;

	public function setClasses(int $amount)
	{
		$this->classes = $amount;
	}

	public function setCoveredClasses(int $amount)
	{
		$this->coveredClasses = $amount;
	}

	public function setMethods(int $amount)
	{
		$this->methods = $amount;
	}

	public function setCoveredMethods(int $amount)
	{
		$this->coveredMethods = $amount;
	}
}