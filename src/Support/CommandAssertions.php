<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Support;

use Myerscode\Acorn\Framework\Console\Command;
use PHPUnit\Framework\Assert as PHPUnit;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * PHPUnit-style assertions for Acorn commands.
 *
 * Extends PHPUnit\Framework\Assert so these read and behave like the native
 * assertions (static, throwing PHPUnit\Framework\AssertionFailedError): e.g.
 *
 *   CommandAssertions::assertCommandHasName($command, 'make:command');
 *   CommandAssertions::assertCommandExits($command, 0, ['name' => 'Rupert']);
 *
 * The fluent TestableCommand wrapper delegates to these.
 */
class CommandAssertions extends PHPUnit
{
    /**
     * Run the command and assert it exits with the given status code.
     *
     * @param array<string, mixed> $arguments
     * @param list<string> $promptAnswers
     */
    public static function assertCommandExits(Command $command, int $expected, array $arguments = [], array $promptAnswers = []): void
    {
        $tester = new CommandTester($command);

        if ($promptAnswers !== []) {
            $tester->setInputs($promptAnswers);
        }

        $tester->execute($arguments);

        self::assertSame(
            $expected,
            $tester->getStatusCode(),
            sprintf('Failed asserting that command exits with code %d; got %d.', $expected, $tester->getStatusCode()),
        );
    }

    public static function assertCommandHasArgument(Command $command, string $name): void
    {
        self::assertTrue(
            $command->getDefinition()->hasArgument($name),
            sprintf('Failed asserting that command defines argument [%s].', $name),
        );
    }

    /**
     * @param class-string $class
     */
    public static function assertCommandHasClassName(Command $command, string $class): void
    {
        self::assertInstanceOf($class, $command);
    }

    public static function assertCommandHasDescription(Command $command, string $description): void
    {
        self::assertSame($description, $command->getDescription());
    }
    public static function assertCommandHasName(Command $command, string $name): void
    {
        self::assertTrue(
            $command->getName() === $name || in_array($name, $command->getAliases(), true),
            sprintf('Failed asserting that command is callable as [%s]. Name: [%s].', $name, (string) $command->getName()),
        );
    }

    public static function assertCommandHasOption(Command $command, string $name): void
    {
        self::assertTrue(
            $command->getDefinition()->hasOption($name),
            sprintf('Failed asserting that command defines option [%s].', $name),
        );
    }

    public static function assertCommandHidden(Command $command): void
    {
        self::assertTrue($command->isHidden(), 'Failed asserting that command is hidden.');
    }

    public static function assertCommandVisible(Command $command): void
    {
        self::assertFalse($command->isHidden(), 'Failed asserting that command is visible.');
    }
}
