<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing;

use Myerscode\Acorn\Testing\Support\Assertions;
use Stringable;

class CommandResult implements Stringable
{
    public function __construct(
        public readonly string $output,
        public readonly int $exitCode,
        public readonly string $errorOutput = '',
    ) {
        //
    }

    /**
     * The captured output, so a CommandResult can be used anywhere the old
     * string return of call() was expected (e.g. assertStringContainsString).
     */
    public function __toString(): string
    {
        return $this->output;
    }

    /**
     * Assert the error output (stderr) contains the given string.
     *
     * Only meaningful when the command was run with separated streams
     * (e.g. runCommandCapturingStreams()); with merged output stderr is empty.
     */
    public function assertErrorOutputContains(string $expected): self
    {
        Assertions::assertStringContainsString($expected, $this->errorOutput);

        return $this;
    }

    /**
     * Assert the error output (stderr) is empty.
     */
    public function assertErrorOutputEmpty(): self
    {
        Assertions::assertSame('', trim($this->errorOutput), 'Expected error output to be empty but got: ' . $this->errorOutput);

        return $this;
    }

    /**
     * Assert the error output (stderr) does not contain the given string.
     */
    public function assertErrorOutputMissing(string $unexpected): self
    {
        Assertions::assertStringNotContainsString($unexpected, $this->errorOutput);

        return $this;
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
     * Assert the output is empty.
     */
    public function assertOutputEmpty(): self
    {
        Assertions::assertSame('', trim($this->output), 'Expected output to be empty but got: ' . $this->output);

        return $this;
    }

    /**
     * Assert the output equals the given string exactly (after trimming).
     */
    public function assertOutputEquals(string $expected): self
    {
        Assertions::assertSame($expected, trim($this->output));

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
