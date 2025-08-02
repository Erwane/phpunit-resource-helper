<?php
/**
 * This file is part of PHPUnit resources helpers library
 *
 * @copyright   Copyright (c) Erwane BRETON
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
declare(strict_types=1);

namespace ResourceHelper\Test\TestCase;

use PHPUnit\Framework\TestCase;
use ResourceHelper\ResourceHelper;

/**
 * @uses   \ResourceHelper\ResourceHelper
 * @covers \ResourceHelper\ResourceHelper
 */
class ResourceHelperTest extends TestCase
{
    protected $_currentBaseDir;
    protected $_currentTmpDir;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->_currentBaseDir = ResourceHelper::getBaseDir();
        $this->_currentTmpDir = ResourceHelper::getTmpDir();
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        ResourceHelper::setBaseDir($this->_currentBaseDir);
        ResourceHelper::setTmpDir($this->_currentTmpDir);
    }

    /**
     * Test getBaseDir auto set dir if empty.
     */
    public function testGetBaseDirEmpty(): void
    {
        ResourceHelper::setBaseDir('');
        $path = ResourceHelper::getBaseDir();

        $this->assertEquals(
            dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR,
            $path
        );
    }

    public function testGetTmpDirEmpty(): void
    {
        ResourceHelper::setTmpDir('');
        $path = ResourceHelper::getTmpDir();

        $this->assertEquals(
            '/tmp/erwane-phpunit-resource-helper/',
            $path
        );
    }

    public function testGetTmpTestPath(): void
    {
        $path = ResourceHelper::getTmpTestPath($this);

        $this->assertEquals(
            '/tmp/erwane-phpunit-resource-helper/ResourceHelperTest_testGetTmpTestPath/',
            $path
        );
    }

    public function testCreateTmpTestDir(): void
    {
        $path = ResourceHelper::createTmpTestDir($this);
        $this->assertDirectoryExists($path);
        $this->assertDirectoryIsWritable($path);
        rmdir($path);
    }

    public function testDestroyTmpTestDir(): void
    {
        $path = ResourceHelper::getTmpTestPath($this);
        mkdir($path);
        touch($path . 'testing.file');

        $this->assertDirectoryExists($path);
        ResourceHelper::destroyTmpTestDir($this);
        $this->assertDirectoryNotExists($path);
    }
}
