<?php
/**
 * This file is part of PHPUnit resources helpers library
 *
 * @copyright   Copyright (c) Erwane BRETON
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
declare(strict_types=1);

namespace ResourceHelper\Test\TestCase\Extension;

use PHPUnit\Event\Code;
use PHPUnit\Event\Telemetry;
use PHPUnit\Event\Test\AfterTestMethodFinished;
use PHPUnit\Event\TestData\TestDataCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\MetadataCollection;
use PHPUnit\Runner\Version;
use ResourceHelper\Extension\CleanupSubscriber;
use ResourceHelper\ResourceHelper;
use function hrtime;

#[CoversClass(CleanupSubscriber::class)]
class CleanupSubscriberTest extends TestCase
{
    final protected function telemetryInfo(): Telemetry\Info
    {
        if (version_compare(Version::id(), '10.1', '<')) {
            $snapshot = new Telemetry\Snapshot(
                Telemetry\HRTime::fromSecondsAndNanoseconds(...hrtime()),
                Telemetry\MemoryUsage::fromBytes(1000),
                Telemetry\MemoryUsage::fromBytes(2000),
            );
        } elseif (version_compare(Version::id(), '10.3', '<')) {
            $snapshot = new Telemetry\Snapshot(
                Telemetry\HRTime::fromSecondsAndNanoseconds(...hrtime()),
                Telemetry\MemoryUsage::fromBytes(1000),
                Telemetry\MemoryUsage::fromBytes(2000),
                new Telemetry\GarbageCollectorStatus(0, 0, 0, 0, false, false, false, 0),
            );
        } else {
            $snapshot = new Telemetry\Snapshot(
                Telemetry\HRTime::fromSecondsAndNanoseconds(...hrtime()),
                Telemetry\MemoryUsage::fromBytes(1000),
                Telemetry\MemoryUsage::fromBytes(2000),
                new Telemetry\GarbageCollectorStatus(0, 0, 0, 0, 0.0, 0.0, 0.0, 0.0, false, false, false, 0),
            );
        }

        return new Telemetry\Info(
            $snapshot,
            Telemetry\Duration::fromSecondsAndNanoseconds(123, 456),
            Telemetry\MemoryUsage::fromBytes(2000),
            Telemetry\Duration::fromSecondsAndNanoseconds(234, 567),
            Telemetry\MemoryUsage::fromBytes(3000),
        );
    }

    final protected function valueObject(): Code\TestMethod
    {
        return new Code\TestMethod(
            'FooTest',
            'testBar',
            'FooTest.php',
            1,
            Code\TestDoxBuilder::fromClassNameAndMethodName('Foo', 'bar'),
            MetadataCollection::fromArray([]),
            TestDataCollection::fromArray([]),
        );
    }

    public function testNotify(): void
    {
        if (version_compare(Version::id(), '10.0.0', '<')) {
            $this->markTestSkipped('Subscribers require phpunit >=10');
        }

        ResourceHelper::createTmpTestDir($this);

        $sub = new CleanupSubscriber();

        $telemetryInfo = $this->telemetryInfo();
        if (version_compare(Version::id(), '12.1.0', '>=')) {
            $test = $this->valueObject();
        } else {
            $test = 'testNotify';
        }
        $calledMethods = [
            new Code\ClassMethod(static::class, 'testNotify'),
        ];

        $event = new AfterTestMethodFinished($telemetryInfo, $test, ...$calledMethods);

        $sub->notify($event);

        $this->assertDirectoryDoesNotExist(ResourceHelper::getTmpTestPath($this));
    }
}
