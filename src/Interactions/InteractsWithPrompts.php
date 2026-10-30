<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Framework\Console\Command;
use Myerscode\Acorn\Testing\CommandResult;
use Symfony\Component\Console\Tester\CommandTester;

trait InteractsWithPrompts
{
    use InteractsWithApplication;

    /**
     * Call a command with simulated prompt answers.
     *
     * The answers array should be ordered to match the sequence of prompts
     * the command will ask.
     *
     * @param string[] $answers Answers to feed to interactive prompts (in order)
     * @param array<string, mixed> $arguments Command arguments/options
     */
    public function callWithPrompts(string|Command $command, array $answers, array $arguments = []): CommandResult
    {
        if (is_string($command)) {
            $command = $this->application()->find($command);
        } else {
            $this->application()->addCommand($command);
        }

        $tester = new CommandTester($command);
        $tester->setInputs($answers);
        $tester->execute($arguments);

        return new CommandResult(
            output: $tester->getDisplay(),
            exitCode: $tester->getStatusCode(),
        );
    }
}
