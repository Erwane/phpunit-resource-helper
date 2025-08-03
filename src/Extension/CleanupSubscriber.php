<?php
/**
 * This file is part of PHPUnit resources helpers library
 *
 * @copyright   Copyright (c) Erwane BRETON
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
declare(strict_types=1);

namespace ResourceHelper\Extension;

use PHPUnit\Event\Test\AfterTestMethodFinished;
use PHPUnit\Event\Test\AfterTestMethodFinishedSubscriber;
use ResourceHelper\ResourceHelper;

/**
 * Clean successful tests tmp directory.
 */
class CleanupSubscriber implements AfterTestMethodFinishedSubscriber
{
    public function notify(AfterTestMethodFinished $event): void
    {
        $methods = $event->calledMethods();
        foreach ($methods as $method) {
            ResourceHelper::destroyTmpTestDir($method->className() . '::' . $method->methodName());
        }
    }
}
