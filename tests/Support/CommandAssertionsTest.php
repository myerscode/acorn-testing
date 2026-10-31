<?php

declare(strict_types=1);

namespace Tests\Support;

use Myerscode\Acorn\Testing\Support\CommandAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\CorgiCommand;

final class CommandAssertionsTest extends TestCase
{
    public function testAssertExitsFailsOnWrongCode(): void
    {
        $this->expectException(AssertionFailedError::class);

        CommandAssertions::assertCommandExits(new CorgiCommand(), 0, ['name' => 'Arthur']);
    }

    public function testAssertExitsWithExpectedCode(): void
    {
        CommandAssertions::assertCommandExits(new CorgiCommand(), 0, ['name' => 'Rupert']);
        CommandAssertions::assertCommandExits(new CorgiCommand(), 2, ['name' => 'Arthur']);
    }

    public function testAssertNameFailsForWrongName(): void
    {
        $this->expectException(AssertionFailedError::class);

        CommandAssertions::assertCommandHasName(new CorgiCommand(), 'corgi:bark');
    }

    public function testAssertOptionFailsForUnknownOption(): void
    {
        $this->expectException(AssertionFailedError::class);

        CommandAssertions::assertCommandHasOption(new CorgiCommand(), 'quiet');
    }

    public function testAssertsDescriptionArgumentsAndOptions(): void
    {
        $command = new CorgiCommand();

        CommandAssertions::assertCommandHasDescription($command, 'Greets a corgi.');
        CommandAssertions::assertCommandHasArgument($command, 'name');
        CommandAssertions::assertCommandHasOption($command, 'loud');
    }
    public function testAssertsNameAndClass(): void
    {
        $command = new CorgiCommand();

        CommandAssertions::assertCommandHasName($command, 'corgi:greet');
        // Resolvable by its fully-qualified class name (an alias) too.
        CommandAssertions::assertCommandHasName($command, CorgiCommand::class);
        CommandAssertions::assertCommandHasClassName($command, CorgiCommand::class);
    }

    public function testVisibilityAssertions(): void
    {
        CommandAssertions::assertCommandVisible(new CorgiCommand());

        $this->expectException(AssertionFailedError::class);
        CommandAssertions::assertCommandHidden(new CorgiCommand());
    }
}
