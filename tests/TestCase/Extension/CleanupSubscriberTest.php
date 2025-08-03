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

use Composer\InstalledVersions;
use PHPUnit\Event\Code;
use PHPUnit\Event\Telemetry;
use PHPUnit\Event\Test\AfterTestMethodFinished;
use PHPUnit\Event\TestData\TestDataCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\MetadataCollection;
use ResourceHelper\Extension\CleanupSubscriber;
use ResourceHelper\ResourceHelper;
use function hrtime;

#[CoversClass(CleanupSubscriber::class)]
class CleanupSubscriberTest extends TestCase
{
    final protected function telemetryInfo(): Telemetry\Info
    {
        return new Telemetry\Info(
            new Telemetry\Snapshot(
                Telemetry\HRTime::fromSecondsAndNanoseconds(...hrtime()),
                Telemetry\MemoryUsage::fromBytes(1000),
                Telemetry\MemoryUsage::fromBytes(2000),
                new Telemetry\GarbageCollectorStatus(0, 0, 0, 0, 0.0, 0.0, 0.0, 0.0, false, false, false, 0),
            ),
            Telemetry\Duration::fromSecondsAndNanoseconds(123, 456),
            Telemetry\MemoryUsage::fromBytes(2000),
            Telemetry\Duration::fromSecondsAndNanoseconds(234, 567),
            Telemetry\MemoryUsage::fromBytes(3000),
        );
    }

    final protected function testValueObject(): Code\TestMethod
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
        ResourceHelper::createTmpTestDir($this);

        $sub = new CleanupSubscriber();

        $telemetryInfo = $this->telemetryInfo();
        $phpunitVersion = InstalledVersions::getVersion('phpunit/phpunit');
        if (version_compare($phpunitVersion, '12.0.0', '>=')) {
            $test = $this->testValueObject();
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
