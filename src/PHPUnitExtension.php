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

use PHPUnit\Runner\AfterSuccessfulTestHook;

/**
 * PHPUnit extension to clean up temp files.
 */
class PHPUnitExtension implements AfterSuccessfulTestHook
{
    /**
     * Cleanup test files if case of successful result.
     *
     * @param string $test Test name with namespace
     * @param float $time Test duration time
     * @return void
     */
    public function executeAfterSuccessfulTest(string $test, float $time): void
    {
        ResourceHelper::destroyTmpTestDir($test);
    }
}
