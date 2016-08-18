<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Models\Comment;
use Documentor\src\Application\Views\ClassView;
use Documentor\src\Application\Views\DocView;
use Documentor\src\Application\Views\MethodView;
use Documentor\src\Application\Views\TableOfContentsView;
use phpOMS\System\File\Directory;
use phpOMS\System\File\File;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Views\ViewAbstract;

class ClassController
{
	private $destination = '';
	private $codeCoverage = null;
	private $unitTest = null;
	private $files = [];
	private $loc = [];

	public function __construct(string $destination, CodeCoverageController $codeCoverage, UnitTestController $unitTest)
	{
		$this->destination = rtrim($destination, '/\\');
		$this->codeCoverage = $codeCoverage;
		$this->unitTest = $unitTest;

		$this->createBaseFiles();
	}

	public function parse(File $file) 
	{
		$classView = $this->parseClass($file->getPath());

		if($classView->getPath() !== '') {
			$this->outputRender($classView);
		}
	}

	public function createSearchSet()
	{
		$js = 'var searchDataset = [];';
		foreach($this->files as $file) {
			$js .= "\n" . 'searchDataset.push([\'' . str_replace('\\', '\\\\', $file[0]) . '\', \'' . $file[1] . '\']);';
		}

		file_put_contents($this->destination . '/js/searchDataset.js', $js);
	}

	public function createTableOfContents() 
	{
		$tocView = new TableOfContentsView();
		$tocView->setPath($this->destination . '/tableOfContents' . '.html');
		$tocView->setBase($this->destination);
		$tocView->setTemplate('/Documentor/src/Theme/tableOfContents');
		$tocView->setTitle('Table of Contents');
		$tocView->setSection('Documentation');

		$this->outputRender($tocView);
	}

	private function parseClass(string $path) : DocView
	{
		$classView = new ClassView();
		$path = str_replace('\\', '/', $path);

		try {
			include_once $path;

			$this->loc = file($path);

			$className = substr($path, strlen(realpath(__DIR__ . '/../../../../')), -4);
			$className = str_replace('/', '\\', $className);
			$class = new \ReflectionClass($className);

			$this->files[] = [$class->getName(), $class->getShortName()];
			$outPath = $this->destination . '/' . str_replace('\\', '/', $class->getName());

			$classView->setPath($outPath . '.html');
			$classView->setBase($this->destination);
			$classView->setTemplate('/Documentor/src/Theme/class');
			$classView->setTitle($class->getShortName());
			$classView->setSection('Documentation');

			$classView->setReflection($class);
			$classView->setComment(new Comment($class->getDocComment()));
			$classView->setTest([]);
			$classView->setCoverage($this->codeCoverage->getClass($class->getName()) ?? []);

			$methods = $class->getMethods();
			foreach($methods as $method) {
				$this->parseMethod($method, $outPath . '-' . $method->getShortName() . '.html', $class->getName());
				$this->files[] = [$class->getName() . '-' . $method->getShortName(), $class->getShortName() . '-' . $method->getShortName()];
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

	private function parseMethod(\ReflectionMethod $method, string $destination, string $className) 
	{
		$methodView = new MethodView();
		$methodView->setTemplate('/Documentor/src/Theme/method');
		$methodView->setBase($this->destination);
		$methodView->setReflection($method);
		$docs = $method->getDocComment();

		try {
			if(strpos($docs, '@inheritdoc') !== false) {
				$comment = new Comment($method->getPrototype()->getDocComment());
			} else {
				$comment = new Comment($docs);
			}
		} catch (\Exception $e) {
			$comment = new Comment($docs);
		}

		$methodView->setComment($comment);
		$methodView->setPath($destination);
		$methodView->setTest([]);
		$methodView->setCoverage($this->codeCoverage->getMethod($className, $method->getShortName()) ?? []);
		$methodView->setTitle($method->getDeclaringClass()->getShortName() . ' ~ ' . $method->getShortName());
		$methodView->setSection('Documentation');
		$methodView->setCode(implode('', array_slice($this->loc, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1)));

		$this->outputRender($methodView);
	}

	private function createBaseFiles()
	{
		try {
			File::copy(__DIR__ . '/../../Theme/css/styles.css', $this->destination . '/css/styles.css');
			File::copy(__DIR__ . '/../../Theme/js/documentor.js', $this->destination . '/js/documentor.js');

			$images = new Directory(__DIR__ . '/../../Theme/img');
			foreach($images as $image) {
				if($image instanceof File) {
					File::copy($image->getPath(), $this->destination . '/img/' . $image->getName());
				}
			}
		} catch(\Exception $e) {
			echo $e->getMessage();
		}
	}

	private function outputRender(ViewAbstract $view)
	{
		Directory::createPath(dirname($view->getPath()), '0644', true);
		file_put_contents($view->getPath(), $view->render());
	}
}