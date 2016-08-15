<?php

namespace Documentor\src\Application;

use Documentor\src\Application\Controllers\ClassController;
use Documentor\src\Application\Controllers\CodeCoverageController;
use Documentor\src\Application\Controllers\UnitTestController;
use phpOMS\System\File\Directory;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Utils\StringUtils;

class Application
{
    private $classController = null;
    private $codeCoverageController = null;
    private $unitTestController = null;

    public function __construct(array $argv)
    {
        $source       = ArrayUtils::getArg('-s', $argv);
        $destination  = ArrayUtils::getArg('-d', $argv);
        $unitTest     = ArrayUtils::getArg('-u', $argv);
        $codeCoverage = ArrayUtils::getArg('-c', $argv);
        $sources      = new Directory($source, '*');

        $this->codeCoverageController = new CodeCoverageController($destination);
        $this->unitTestController     = new UnitTestController($destination);
        $this->classController        = new ClassController($destination, $this->codeCoverageController, $this->unitTestController);

        $this->codeCoverageController->parse($codeCoverage);
        $this->unitTestController->parse($unitTest);
        $this->parse($sources);
    }

    public function parse(Directory $sources)
    {
        foreach ($sources as $source) {
            if ($source instanceof Directory) {
                $this->parse($source);
            } else {
                if (StringUtils::endsWith($source->getPath(), '.php')) {
                    $this->classController->parse($source);
                }
            }
        }
    }
}