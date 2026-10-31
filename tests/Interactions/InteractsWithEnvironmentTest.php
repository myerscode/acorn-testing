<?php

declare(strict_types=1);

namespace Tests\Interactions;

use Myerscode\Acorn\Testing\Interactions\InteractsWithEnvironment;
use PHPUnit\Framework\TestCase;

final class InteractsWithEnvironmentTest extends TestCase
{
    public function testRestoreReturnsAPreexistingVariableToItsOriginalValue(): void
    {
        putenv('ACORN_EXISTING=Arthur');
        $env = $this->subject();

        $env->setEnvironment('ACORN_EXISTING', 'Rupert');
        $this->assertSame('Rupert', getenv('ACORN_EXISTING'));

        $env->restoreEnvironment();

        $this->assertSame('Arthur', getenv('ACORN_EXISTING'));

        putenv('ACORN_EXISTING');
    }

    public function testRestoreUnsetsAVariableThatDidNotExist(): void
    {
        $env = $this->subject();
        $this->assertFalse(getenv('ACORN_NEW_VAR'));

        $env->setEnvironment('ACORN_NEW_VAR', 'Gerald');
        $this->assertSame('Gerald', getenv('ACORN_NEW_VAR'));

        $env->restoreEnvironment();

        $this->assertFalse(getenv('ACORN_NEW_VAR'));
        $this->assertArrayNotHasKey('ACORN_NEW_VAR', $_ENV);
    }

    public function testSetAndReadEnvironment(): void
    {
        $env = $this->subject();

        $env->setEnvironment('ACORN_CORGI', 'Rupert');

        $this->assertSame('Rupert', $env->getEnvironment('ACORN_CORGI'));
        $this->assertSame('Rupert', getenv('ACORN_CORGI'));
        $this->assertSame('Rupert', $_ENV['ACORN_CORGI']);

        $env->restoreEnvironment();
    }

    public function testSetEnvironmentValuesSetsSeveralAtOnce(): void
    {
        $env = $this->subject();

        $env->setEnvironmentValues(['ACORN_A' => 'Rupert', 'ACORN_B' => 'Gerald']);

        $this->assertSame('Rupert', getenv('ACORN_A'));
        $this->assertSame('Gerald', getenv('ACORN_B'));

        $env->restoreEnvironment();

        $this->assertFalse(getenv('ACORN_A'));
        $this->assertFalse(getenv('ACORN_B'));
    }
    private function subject(): object
    {
        return new class () {
            use InteractsWithEnvironment;
        };
    }
}
