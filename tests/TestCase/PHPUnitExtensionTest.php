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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ResourceHelper\PHPUnitExtension;

#[CoversClass(PHPUnitExtension::class)]
class PHPUnitExtensionTest extends TestCase
{
    public function testAfterSuccessfulTest(): void
    {
        $ext = new PHPUnitExtension();
        $this->assertTrue(method_exists($ext, 'bootstrap'));
    }
}
