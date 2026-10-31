<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Testable;

use Myerscode\Acorn\Framework\Console\Command;
use Myerscode\Acorn\Testing\CommandResult;
use Myerscode\Acorn\Testing\Support\CommandAssertions;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * A fluent, chainable wrapper around a command under test.
 *
 * Convenience layer over the PHPUnit-style CommandAssertions: each assert*
 * method delegates to the matching static assertion and returns $this so
 * they can chain. The predicate methods (hasName, hasClassName, isHidden,
 * ...) return booleans for use in custom assertions.
 */
final class TestableCommand
{
    public function __construct(private readonly Command $command)
    {
        //
    }

    /**
     * Assert the command exits with the given status code when run.
     *
     * @param array<string, mixed> $arguments
     * @param list<string> $promptAnswers
     */
    public function assertExits(int $expected, array $arguments = [], array $promptAnswers = []): self
    {
        CommandAssertions::assertCommandExits($this->command, $expected, $arguments, $promptAnswers);

        return $this;
    }

    /**
     * Assert the command exits successfully (code 0) when run.
     *
     * @param array<string, mixed> $arguments
     * @param list<string> $promptAnswers
     */
    public function assertExitsSuccessfully(array $arguments = [], array $promptAnswers = []): self
    {
        CommandAssertions::assertCommandExits($this->command, 0, $arguments, $promptAnswers);

        return $this;
    }

    public function assertHasArgument(string $name): self
    {
        CommandAssertions::assertCommandHasArgument($this->command, $name);

        return $this;
    }

    /**
     * @param class-string $class
     */
    public function assertHasClassName(string $class): self
    {
        CommandAssertions::assertCommandHasClassName($this->command, $class);

        return $this;
    }

    public function assertHasDescription(string $description): self
    {
        CommandAssertions::assertCommandHasDescription($this->command, $description);

        return $this;
    }

    // ---- Definition assertions (delegate to CommandAssertions) -----------

    public function assertHasName(string $name): self
    {
        CommandAssertions::assertCommandHasName($this->command, $name);

        return $this;
    }

    public function assertHasOption(string $name): self
    {
        CommandAssertions::assertCommandHasOption($this->command, $name);

        return $this;
    }

    public function assertHidden(): self
    {
        CommandAssertions::assertCommandHidden($this->command);

        return $this;
    }

    public function assertVisible(): self
    {
        CommandAssertions::assertCommandVisible($this->command);

        return $this;
    }

    public function command(): Command
    {
        return $this->command;
    }

    public function hasArgument(string $name): bool
    {
        return $this->command->getDefinition()->hasArgument($name);
    }

    /**
     * Is this command an instance of the given class?
     *
     * @param class-string $class
     */
    public function hasClassName(string $class): bool
    {
        return $this->command instanceof $class;
    }

    public function hasDescription(string $description): bool
    {
        return $this->command->getDescription() === $description;
    }

    // ---- Definition predicates -------------------------------------------

    /**
     * Does the command resolve under this call name (its signature name or any
     * alias, including its fully-qualified class name)?
     */
    public function hasName(string $name): bool
    {
        return $this->command->getName() === $name
            || in_array($name, $this->command->getAliases(), true);
    }

    public function hasOption(string $name): bool
    {
        return $this->command->getDefinition()->hasOption($name);
    }

    public function isHidden(): bool
    {
        return $this->command->isHidden();
    }

    // ---- Execution -------------------------------------------------------

    /**
     * Run the command with the given input and return the result for further
     * output/exit-code assertions.
     *
     * @param array<string, mixed> $arguments
     * @param list<string> $promptAnswers
     */
    public function run(array $arguments = [], array $promptAnswers = []): CommandResult
    {
        $tester = new CommandTester($this->command);

        if ($promptAnswers !== []) {
            $tester->setInputs($promptAnswers);
        }

        $tester->execute($arguments);

        return new CommandResult(
            output: $tester->getDisplay(),
            exitCode: $tester->getStatusCode(),
        );
    }
}
