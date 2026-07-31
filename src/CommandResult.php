<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing;

use Myerscode\Acorn\Testing\Support\Assertions;

class CommandResult
{
    public function __construct(
        public readonly string $output,
        public readonly int $exitCode,
    ) {
        //
    }

    /**
     * Assert the command exited with a specific exit code.
     */
    public function assertExitCode(int $expected): self
    {
        Assertions::assertSame($expected, $this->exitCode);

        return $this;
    }

    /**
     * Assert the command exited with a failure status code (non-zero).
     */
    public function assertFailed(): self
    {
        Assertions::assertNotSame(0, $this->exitCode, 'Expected non-zero exit code but got 0');

        return $this;
    }

    /**
     * Assert the output contains the given string.
     */
    public function assertOutputContains(string $expected): self
    {
        Assertions::assertStringContainsString($expected, $this->output);

        return $this;
    }

    /**
     * Assert the output matches a regular expression.
     */
    public function assertOutputMatches(string $pattern): self
    {
        Assertions::assertMatchesRegularExpression($pattern, $this->output);

        return $this;
    }

    /**
     * Assert the output does not contain the given string.
     */
    public function assertOutputMissing(string $unexpected): self
    {
        Assertions::assertStringNotContainsString($unexpected, $this->output);

        return $this;
    }

    /**
     * Assert the command exited with a successful status code (0).
     */
    public function assertSuccessful(): self
    {
        Assertions::assertSame(0, $this->exitCode, 'Expected exit code 0 but got ' . $this->exitCode);

        return $this;
    }
}
