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
use PHPUnit\Runner\Version;
use ResourceHelper\PHPUnitHooks;
use ResourceHelper\ResourceHelper;

/**
 * @uses   \ResourceHelper\PHPUnitHooks
 * @covers \ResourceHelper\PHPUnitHooks
 */
class PHPUnitHooksTest extends TestCase
{
    public function testAfterSuccessfulTest(): void
    {
        if (version_compare(Version::id(), '10.0.0', '>=')) {
            $this->markTestSkipped('Subscribers require phpunit <10');
        }

        $ext = new PHPUnitHooks();
        $this->assertTrue(method_exists($ext, 'executeAfterSuccessfulTest'));

        // Create tmp test dir
        ResourceHelper::createTmpTestDir($this);

        $ext->executeAfterSuccessfulTest($this->toString(), 0.3);

        if (version_compare(Version::id(), '9.0', '<')) {
            $this->assertDirectoryNotExists(ResourceHelper::getTmpTestPath($this));
        } else {
            $this->assertDirectoryDoesNotExist(ResourceHelper::getTmpTestPath($this));
        }
    }
}
