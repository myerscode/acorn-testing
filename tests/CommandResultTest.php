<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Acorn\Testing\CommandResult;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

final class CommandResultTest extends TestCase
{
    public function testExposesOutputAndExitCode(): void
    {
        $result = new CommandResult("There are 3 Corgis\n", 0);

        $this->assertSame("There are 3 Corgis\n", $result->output);
        $this->assertSame(0, $result->exitCode);
    }

    public function testFailureAssertions(): void
    {
        $result = new CommandResult('boom', 3);

        $result->assertFailed()->assertExitCode(3);

        $this->expectException(AssertionFailedError::class);
        $result->assertSuccessful();
    }

    public function testIsStringableToItsOutputForBackwardCompatibility(): void
    {
        // call() used to return a string; CommandResult must coerce to the
        // output so existing assertStringContainsString(...) calls keep working.
        $result = new CommandResult('Rupert and Gerald', 0);

        $this->assertSame('Rupert and Gerald', (string) $result);
        $this->assertStringContainsString('Gerald', (string) $result);
    }

    public function testOutputContainsAndMissing(): void
    {
        $result = new CommandResult('Rupert and Gerald', 0);

        $result->assertOutputContains('Rupert')
            ->assertOutputContains('Gerald')
            ->assertOutputMissing('Arthur');

        $this->expectException(AssertionFailedError::class);
        $result->assertOutputContains('Arthur');
    }

    public function testOutputEmpty(): void
    {
        new CommandResult("  \n", 0)->assertOutputEmpty();

        $this->expectException(AssertionFailedError::class);
        new CommandResult('Gerald', 0)->assertOutputEmpty();
    }

    public function testOutputEqualsTrimsWhitespace(): void
    {
        $result = new CommandResult("  Arthur  \n", 0);

        $result->assertOutputEquals('Arthur');

        $this->expectException(AssertionFailedError::class);
        $result->assertOutputEquals('Rupert');
    }

    public function testOutputMatchesRegex(): void
    {
        $result = new CommandResult('There are 3 Corgis', 0);

        $result->assertOutputMatches('/\d+ Corgis/');

        $this->expectException(AssertionFailedError::class);
        $result->assertOutputMatches('/\d+ Spaniels/');
    }

    public function testSuccessAndExitCodeAssertions(): void
    {
        $result = new CommandResult('Rupert', 0);

        $result->assertSuccessful()->assertExitCode(0);

        $this->expectException(AssertionFailedError::class);
        $result->assertFailed();
    }
}
