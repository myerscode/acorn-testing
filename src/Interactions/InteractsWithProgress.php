<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Framework\Console\Command;
use Myerscode\Acorn\Testing\CommandResult;
use Symfony\Component\Console\Tester\CommandTester;

trait InteractsWithProgress
{
    use InteractsWithApplication;

    /**
     * Call a command and capture its output for progress assertions.
     *
     * Useful for testing commands that use withProgressBar, withSpinner, or withTask.
     *
     * @param array<string, mixed> $arguments Command arguments/options
     */
    public function callAndCaptureOutput(string|Command $command, array $arguments = []): CommandResult
    {
        if (is_string($command)) {
            $command = $this->application()->find($command);
        } else {
            $this->application()->addCommand($command);
        }

        $tester = new CommandTester($command);
        $tester->execute($arguments);

        return new CommandResult(
            output: $tester->getDisplay(),
            exitCode: $tester->getStatusCode(),
        );
    }
}
