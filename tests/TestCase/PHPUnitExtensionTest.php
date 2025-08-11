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
use ResourceHelper\PHPUnitExtension;
use ResourceHelper\ResourceHelper;

/**
 * @uses   \ResourceHelper\PHPUnitExtension
 * @covers \ResourceHelper\PHPUnitExtension
 */
class PHPUnitExtensionTest extends TestCase
{
    public function testAfterSuccessfulTest(): void
    {
        $ext = new PHPUnitExtension();
        $this->assertTrue(method_exists($ext, 'executeAfterSuccessfulTest'));

        // Create tmp test dir
        ResourceHelper::createTmpTestDir($this);

        $ext->executeAfterSuccessfulTest($this->toString(), 0.3);

        $this->assertDirectoryDoesNotExist(ResourceHelper::getTmpTestPath($this));
    }
}
