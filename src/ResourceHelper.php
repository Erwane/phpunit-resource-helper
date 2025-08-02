<?php
/**
 * This file is part of PHPUnit resources helpers library
 *
 * @copyright   Copyright (c) Erwane BRETON
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
declare(strict_types=1);

namespace ResourceHelper;

use Composer\InstalledVersions;
use DirectoryIterator;
use PHPUnit\Framework\TestCase;

/**
 * ResourceHelper
 */
class ResourceHelper
{
    /**
     * @var string
     */
    protected static $_baseDir;

    /**
     * @var string
     */
    protected static $_tmpDir;

    /**
     * Get resource base dir.
     *
     * @return string
     */
    public static function getBaseDir(): string
    {
        if (empty(self::$_baseDir)) {
            self::$_baseDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
        }

        return self::$_baseDir;
    }

    /**
     * Set resources base dir.
     *
     * @param string $baseDir New resources base dir
     * @return void
     */
    public static function setBaseDir(string $baseDir): void
    {
        self::$_baseDir = $baseDir;
    }

    /**
     * Get resources tmp dir.
     *
     * @return string
     */
    public static function getTmpDir(): string
    {
        if (empty(self::$_tmpDir)) {
            $root = InstalledVersions::getRootPackage();
            $project = preg_replace('#[._ /-]#', '-', $root['name']);

            self::$_tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $project . DIRECTORY_SEPARATOR;
        }

        return self::$_tmpDir;
    }

    /**
     * Set resources tmp dir.
     *
     * @param string $tmpDir New project resources tmp dir
     * @return void
     */
    public static function setTmpDir(string $tmpDir): void
    {
        self::$_tmpDir = $tmpDir;
    }

    /**
     * Build the test dir path from TestCase.
     *
     * @param \PHPUnit\Framework\TestCase|string $test Running test or test method full name
     * @return string
     */
    public static function getTmpTestPath($test): string
    {
        $name = $test;
        if ($test instanceof TestCase) {
            $name = $test->toString();
        }
        $namespace = explode('\\', $name);
        $method = str_replace('::', '_', end($namespace));

        return ResourceHelper::getTmpDir() . $method . DIRECTORY_SEPARATOR;
    }

    /**
     * Create tmp test directory.
     *
     * @param \PHPUnit\Framework\TestCase|string $test Running test or test method full name
     * @return string
     */
    public static function createTmpTestDir($test): string
    {
        self::destroyTmpTestDir($test);

        $dir = self::getTmpTestPath($test);
        mkdir($dir, 0700, true);

        return $dir;
    }

    /**
     * Destroy tmp test directory.
     *
     * @param \PHPUnit\Framework\TestCase|string $test Running test or test method full name
     * @return void
     */
    public static function destroyTmpTestDir($test): void
    {
        $dir = self::getTmpTestPath($test);
        if (is_dir($dir)) {
            /** @var \DirectoryIterator $file */
            foreach (new DirectoryIterator($dir) as $file) {
                if (is_file($file->getPathname())) {
                    unlink($file->getPathname());
                }
            }

            rmdir($dir);
        }
    }
}
