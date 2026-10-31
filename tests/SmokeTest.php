<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Acorn\Testing\AcornTestCase;
use PHPUnit\Framework\TestCase;

/**
 * Minimal smoke test so the suite has at least one test and CI is green.
 * Replace / extend with real coverage as the package grows.
 */
final class SmokeTest extends TestCase
{
    public function testPackageClassesAutoload(): void
    {
        $this->assertTrue(class_exists(AcornTestCase::class));
    }
}
