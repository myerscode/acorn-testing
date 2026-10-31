<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Acorn\Testing\AcornTestCase;

/**
 * Base case for acorn-testing's own integration tests. The package has no app
 * of its own, so point runningFrom() at the tests directory; command fixtures
 * are added as instances rather than discovered.
 */
class BaseTestCase extends AcornTestCase
{
    public function runningFrom(): string
    {
        return __DIR__;
    }
}
