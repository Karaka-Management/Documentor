<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */

namespace Documentor\src\Application;

spl_autoload_register('\Documentor\src\Application\Autoloader::defaultAutoloader');

/**
 * Autoloader class.
 *
 * @category   Framework
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Autoloader
{

    /**
     * Loading classes by namespace + class name.
     *
     * @param string $class Class path
     *
     * @return void
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function defaultAutoloader(string $class)
    {
        if (($classNew = self::exists($class)) !== false) {
            /** @noinspection PhpIncludeInspection */
            include_once $classNew;
        } else {
            throw new \Exception($class);
        }
    }

    /**
     * Check if class exists.
     *
     * @param string $class Class path
     *
     * @return false|string
     *
     * @since  1.0.0
     */
    public static function exists(string $class)
    {
        $class = ltrim($class, '\\');
        $class = str_replace(['_', '\\'], DIRECTORY_SEPARATOR, $class);

        if (file_exists(__DIR__ . '/../../../' . $class . '.php')) {
            return __DIR__ . '/../../../' . $class . '.php';
        } elseif (file_exists(dirname(Phar::running(false)) . '/../../../' . $class . '.php')) {
            return dirname(Phar::running(false)) . '/../../../' . $class . '.php';
        }

        return false;
    }
}
