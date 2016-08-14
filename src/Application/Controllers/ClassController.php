<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Models\Comment;
use Documentor\src\Application\Views\ClassView;
use Documentor\src\Application\Views\DocView;
use Documentor\src\Application\Views\MethodView;
use phpOMS\System\File\Directory;
use phpOMS\System\File\File;
use phpOMS\Views\ViewAbstract;

class ClassController
{
	private $destination = '';
	private $codeCoverage = null;
	private $unitTest = null;

	public function __construct(string $destination, CodeCoverageController $codeCoverage, UnitTestController $unitTest)
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

	private function parseClass(string $path) : DocView
	{
		$classView = new ClassView();
		try {
			include_once $path;

			$className = substr($path, strlen(realpath(__DIR__ . '/../../../../')), -4);
			$className = str_replace('/', '\\', $className);
			$class = new \ReflectionClass($className);
			$outPath = $this->destination . '/' . str_replace('\\', '/', $class->getName());
			$classView->setPath($outPath . '.html');
			$classView->setBase($this->destination);
			$classView->setTemplate('/Documentor/src/Theme/class');

			$classView->setReflection($class);
			$classView->setComment(new Comment($class->getDocComment()));
			$classView->setTest($this->unitTest->getClass($class->getName()));
			$classView->setCoverage($this->codeCoverage->getClass($class->getName()));

			$methods = $class->getMethods();
			foreach($methods as $method) {
				$this->parseMethod($method, $outPath . '-' . $method->getShortName() . '.html');
			}
		} catch(\Exception $e) {
			echo $e->getMessage();
			echo $e->getFile();
		} catch(\Throwable $e) {
			echo $e->getMessage();
			echo $e->getFile();
		} finally {
			return $classView;
		}
	}

	private function parseMethod(\ReflectionMethod $method, string $destination) 
	{
		$methodView = new MethodView();
		$methodView->setTemplate('/Documentor/src/Theme/method');
		$methodView->setReflection($method);
		$methodView->setComment(new Comment($method->getDocComment()));
		$methodView->setPath($destination);
		$methodView->setTest($this->unitTest->getMethod($method->getName()));
		$methodView->setCoverage($this->codeCoverage->getMethod($method->getName()));

		$this->outputRender($methodView);
	}

	private function outputRender(ViewAbstract $view)
	{
		Directory::createPath(dirname($view->getPath()), '0777', true);
		file_put_contents($view->getPath(), $view->render());
	}
}