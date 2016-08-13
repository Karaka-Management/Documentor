<?php

namespace Documentor\src\Application\Controllers;

class ClassController 
{
	private $destination = '';
	private $codeCoverage = null;
	private $unitTest = null;

	public function __construct(string $destination, CodeCoverageController $codeCoverage, UnitTestCoverage $unitTest)
	{
		$this->destination = $destination;
		$this->codeCoverage = $codeCoverage;
		$this->unitTest = $unitTest;
	}

	public function parse(File $file) 
	{
		$classView = $this->parseClass($file->getPath());

		if($classView->getPath() !== '') {
			$this->outputRender($classView);
		}
	}

	private function parseClass(string $path) : ClassView
	{
		$classView = new ClassView();

		try {
			include $path;

			$className = substr($path, strlen(realpath(__DIR__ . '/../../../../')), -4);
			$className = str_replace('/', '\\', $className);
			$class = new \ReflectionClass($className);
			$outPath = $this->destination . '/' . str_replace('\\', '/', $class->getName);
			$classView->setPath($outPath . '.html');

			if($class->isInterface()) {
				$classView->setTemplate('/Documentor/src/Theme/interface');
			} elseif($class->isTrait()) {
				$classView->setTemplate('/Documentor/src/Theme/trait');
			} else {
				$classView->setTemplate('/Documentor/src/Theme/class');
			}

			$classView->setReflection($class);
			$classView->setComment(new Comment($class->getDocComment()));
			$classView->setTest($this->unitTest->getClass($method->getName()));
			$classView->setCoverage($this->codeCoverage->getClass($method->getName()));

			$methods = $class->getMethods();
			foreach($methods as $method) {
				$this->parseMethod($method, $outPath . '-' . $method->getShortName() . '.html');
			}
		} catch(\Exception $e) {
			echo $e->getMessage();
		} finally {
			return $classView;
		}
	}

	private function parseMethod(\ReflectionMethod $method, string $destination) 
	{
		$methodView = new MethodView();
		$methodView->setTemplate('/Documentor/src/Theme/method');
		$methodView->setReflection($method);
		$methodView->setComments(new Comment($method->getDocComment()));
		$methodView->setPath($destination);
		$methodView->setTest($this->unitTest->getMethod($method->getName()));
		$methodView->setCoverage($this->codeCoverage->getMethod($method->getName()));

		$this->outputRender($methodView);
	}

	private function outputRender(View $view)
	{
		file_put_contents($view->getPath(), $view->render());
	}
}