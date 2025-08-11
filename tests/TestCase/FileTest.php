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
use ResourceHelper\File;
use ResourceHelper\ResourceHelper;

/**
 * @uses   \ResourceHelper\File
 * @covers \ResourceHelper\File
 */
class FileTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        ResourceHelper::destroyTmpTestDir($this);
    }

    public function testGetPathUnknown(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Path "unknown.file" not found');
        File::getPath('unknown.file');
    }

    public function testGetPath(): void
    {
        $path = File::getPath('basic.json');
        $this->assertEquals(ResourceHelper::getBaseDir() . 'basic.json', $path);
    }

    public function testGetInfos(): void
    {
        $infos = File::getInfo('basic.json');

        $this->assertSame([
            'path' => ResourceHelper::getBaseDir() . 'basic.json',
            'filename' => 'basic.json',
            'hash' => 'ea52ae55e385714418888d9bbd1ab849855eae3e',
        ], $infos);
    }

    public function testGetContent(): void
    {
        $content = File::getContent('basic.json');

        $expected = <<<JSON
{
    "key": "value",
    "int": 23
}

JSON;
        $this->assertEquals($expected, $content);
    }

    public function testGetCopy(): void
    {
        $copy = File::getCopy('basic.json', $this);
        $expected = ResourceHelper::getTmpDir() . 'FileTest_testGetCopy' . DIRECTORY_SEPARATOR . 'basic.json';
        $this->assertEquals($expected, $copy['path']);
        $this->assertFileExists($copy['path']);
        $this->assertEquals('ea52ae55e385714418888d9bbd1ab849855eae3e', hash_file('sha1', $copy['path']));
    }
}
