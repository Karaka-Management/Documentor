<?php 

namespace Documentor\src\Application;

class Application 
{
	private $classController = null;
	private $codeCoverageController = null;
	private $unitTestController = null;


	public function __construct(array $argv) 
	{
		$source = ArrayUtils::getArg('-s');
		$destination = ArrayUtils::getArg('-d');
		$unitTest = ArrayUtils::getArg('-u');
		$codeCoverage = ArrayUtils::getArg('-c');
		$sources = new Directory('*.php');

		$this->codeCoverageController = new CodeCoverageController($destination);
		$this->unitTestController = new UnitTestController($destination);
		$this->classController = new ClassController($destination, $codeCoverageController, $unitTestController);

		$this->codeCoverageController->parse($codeCoverage);
		$this->unitTestController->parse($unitTest);
		$this->parse($sources);
	}

	public function parse(Directory $sources) 
	{
		foreach($sources as $source) {
			if($source instanceof Directory) {
				$this->parse($source);
			} else {
				$this->classController->parse($source);
			}
		}
	}
}