<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Framework\Console\Command;
use Myerscode\Acorn\Testing\CommandResult;
use Myerscode\Acorn\Testing\Testable\TestableCommand;
use RuntimeException;

trait InteractsWithCommands
{
    use InteractsWithApplication;

    /**
     * Run a command and return its captured output.
     *
     * Kept returning a plain string for backward compatibility with the many
     * callers that pass the result straight to assertStringContainsString()
     * (which is string-typed and does not accept a Stringable). For exit-code
     * and fluent output assertions, use runCommand() or command()->run().
     *
     * @param array<mixed> $userInput
     */
    public function call(string|Command $command, array $userInput = []): string
    {
        return $this->runCommand($command, $userInput)->output;
    }

    /**
     * Wrap a command (by call name, class name, or instance) for fluent
     * definition and execution assertions:
     *
     *   $this->command('make:command')
     *       ->assertHasName('make:command')
     *       ->assertHasClassName(MakeCommandCommand::class)
     *       ->assertHasOption('force')
     *       ->assertExits(0, ['name' => 'Rupert']);
     *
     * For the PHPUnit-static style, call CommandAssertions directly on the
     * wrapped command() instead.
     */
    public function command(string|Command $command): TestableCommand
    {
        if ($command instanceof Command) {
            $this->application()->addCommand($command);

            return new TestableCommand($command);
        }

        $resolved = $this->application()->find($command);

        if (!$resolved instanceof Command) {
            throw new RuntimeException(sprintf('Command [%s] is not an Acorn command.', $command));
        }

        return new TestableCommand($resolved);
    }

    /**
     * Run a command and return the full result (output + exit code) for
     * richer assertions:
     *
     *   $this->runCommand('demo')->assertSuccessful()->assertOutputContains('Rupert');
     *
     * @param array<mixed> $userInput
     */
    public function runCommand(string|Command $command, array $userInput = []): CommandResult
    {
        $input = $this->createInput($userInput);

        $output = $this->createStreamOutput($input);

        if (is_string($command)) {
            $command = $this->application()->find($command);
        } else {
            $this->application()->addCommand($command);
        }

        $exitCode = $command->run($input, $output);

        $stream = $output->getStream();
        $display = '';

        if (is_resource($stream)) {
            rewind($stream);
            $display = (string) stream_get_contents($stream);
        }

        return new CommandResult(
            output: trim(str_replace(PHP_EOL, "\n", $display)),
            exitCode: $exitCode,
        );
    }
}
