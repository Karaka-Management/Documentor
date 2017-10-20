<?php

namespace Documentor\src\Application\Controllers;

use Documentor\src\Application\Models\Comment;
use Documentor\src\Application\Views\ClassView;
use Documentor\src\Application\Views\DocView;
use Documentor\src\Application\Views\MethodView;
use Documentor\src\Application\Views\TableOfContentsView;
use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\Local\File;

class DocumentationController
{
    private $destination = '';
    private $base = '';
    private $sourcePath = '';
    private $codeCoverage = null;
    private $unitTest = null;
    private $files = [];
    private $loc = [];
    private $stats = ['loc' => 0, 'classes' => 0, 'traits' => 0, 'interfaces' => 0, 'abstracts' => 0, 'methods' => 0];
    private $withoutComment = [];

    public function __construct(string $destination, string $base, string $source, CodeCoverageController $codeCoverage, UnitTestController $unitTest)
    {
        $this->destination  = $destination;
        $this->base         = $base;
        $this->codeCoverage = $codeCoverage;
        $this->unitTest     = $unitTest;
        $this->sourcePath  = $source;

        $this->createBaseFiles();
    }

    public function parse(File $file)
    {
        $classView = $this->parseClass($file->getPath());

        if ($classView->getPath() !== '') {
            File::put($classView->getPath(), $classView->render());
        }
    }

    public function createSearchSet()
    {
        $js = 'var searchDataset = [];';
        foreach ($this->files as $file) {
            $js .= "\n" . 'searchDataset.push([\'' . str_replace('\\', '\\\\', $file[0]) . '\', \'' . $file[1] . '\']);';
        }

        file_put_contents($this->destination . '/js/searchDataset.js', $js);
    }

    public function createTableOfContents()
    {
        $tocView = new TableOfContentsView();
        $tocView->setPath($this->destination . '/documentation' . '.html');
        $tocView->setBase($this->base);
        $tocView->setTemplate('/Documentor/src/Theme/documentation');
        $tocView->setTitle('Table of Contents');
        $tocView->setSection('Documentation');
        $tocView->setStats($this->stats);
        $tocView->setWithoutComment($this->withoutComment);

        File::put($tocView->getPath(), $tocView->render());
    }

    private function parseClass(string $path) : DocView
    {
        $classView = new ClassView();
        $path      = str_replace('\\', '/', $path);

        try {
            include_once $path;

            $this->loc = file($path);
            $this->stats['loc'] += count($this->loc);

            $className = substr($path, strlen(rtrim(Directory::parent($this->sourcePath), '/\\')), -4);
            $className = str_replace('/', '\\', $className);
            $class     = new \ReflectionClass($className);

            $this->files[] = [$class->getName(), $class->getShortName()];
            $outPath       = $this->destination . '/' . str_replace('\\', '/', $class->getName());

            $classView->setPath($outPath . '.html');
            $classView->setBase($this->base);
            $classView->setTemplate('/Documentor/src/Theme/class');
            $classView->setTitle($class->getShortName());
            $classView->setSection('Documentation');

            if ($class->isInterface()) {
                $this->stats['interfaces']++;
            } elseif ($class->isTrait()) {
                $this->stats['traits']++;
            } elseif ($class->isAbstract()) {
                $this->stats['abstracts']++;
            } elseif ($class->isUserDefined()) {
                $this->stats['classes']++;
            }

            $classView->setReflection($class);
            $classView->setComment(new Comment($class->getDocComment()));
            $classView->setCoverage($this->codeCoverage->getClass($class->getName()) ?? []);

            $methods = $class->getMethods();
            foreach ($methods as $method) {
                if ($method->isUserDefined()) {
                    $this->parseMethod($method, $outPath . '-' . $method->getShortName() . '.html', $class->getName());
                    $this->files[] = [$class->getName() . '-' . $method->getShortName(), $class->getShortName() . '-' . $method->getShortName()];
                }
            }

        } catch (\Exception $e) {
            echo $e->getMessage(), ' - ', $e->getFile(), ' - ', $e->getLine(), "\n";
        } finally {
            return $classView;
        }
    }

    private function parseMethod(\ReflectionMethod $method, string $destination, string $className)
    {
        $methodView = new MethodView();
        $methodView->setTemplate('/Documentor/src/Theme/method');
        $methodView->setBase($this->base);
        $methodView->setReflection($method);
        $docs = $method->getDocComment();

        try {
            if (strpos($docs, '@inheritdoc') !== false) {
                $comment = new Comment($method->getPrototype()->getDocComment());
            } else {
                $comment = new Comment($docs);
            }
        } catch (\Exception $e) {
            $comment = new Comment($docs);
        }

        $methodView->setComment($comment);
        $methodView->setPath($destination);
        $methodView->setCoverage($this->codeCoverage->getMethod($className, $method->getShortName()) ?? []);
        $methodView->setTitle($method->getDeclaringClass()->getShortName() . ' ~ ' . $method->getShortName());
        $methodView->setSection('Documentation');
        $methodView->setCode(implode('', array_slice($this->loc, $method->getStartLine() - 1, $method->getEndLine() - $method->getStartLine() + 1)));
        $this->stats['methods']++;

        if ($comment->isEmpty()) {
            $this->withoutComment[] = $className . '-' . $method->getShortName();
        }

        File::put($methodView->getPath(), $methodView->render());
    }

    private function createBaseFiles()
    {
        try {
            File::copy(__DIR__ . '/../../Theme/css/styles.css', $this->destination . '/css/styles.css', true);
            File::copy(__DIR__ . '/../../Theme/js/documentor.js', $this->destination . '/js/documentor.js', true);

            $images = new Directory(__DIR__ . '/../../Theme/img');
            foreach ($images as $image) {
                if ($image instanceof File) {
                    File::copy($image->getPath(), $this->destination . '/img/' . $image->getName() . '.' . $image->getExtension(), true);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}