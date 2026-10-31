<?php

declare(strict_types=1);

namespace Tests\Testable;

use Myerscode\Acorn\Testing\Testable\TestableCommand;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\CorgiCommand;

final class TestableCommandTest extends TestCase
{
    public function testFluentAssertionFailsLoudly(): void
    {
        $this->expectException(AssertionFailedError::class);

        new TestableCommand(new CorgiCommand())->assertHasOption('quiet');
    }

    public function testFluentAssertionsChain(): void
    {
        new TestableCommand(new CorgiCommand())
            ->assertHasName('corgi:greet')
            ->assertHasClassName(CorgiCommand::class)
            ->assertHasDescription('Greets a corgi.')
            ->assertHasArgument('name')
            ->assertHasOption('loud')
            ->assertVisible()
            ->assertExits(0, ['name' => 'Gerald'])
            ->assertExitsSuccessfully(['name' => 'Rupert']);
    }
    public function testPredicatesReportDefinition(): void
    {
        $testable = new TestableCommand(new CorgiCommand());

        $this->assertTrue($testable->hasName('corgi:greet'));
        $this->assertTrue($testable->hasClassName(CorgiCommand::class));
        $this->assertTrue($testable->hasDescription('Greets a corgi.'));
        $this->assertTrue($testable->hasArgument('name'));
        $this->assertTrue($testable->hasOption('loud'));
        $this->assertFalse($testable->isHidden());
        $this->assertFalse($testable->hasOption('quiet'));
    }

    public function testRunHonoursFlagsAndFailingExitCode(): void
    {
        $loud = new TestableCommand(new CorgiCommand())->run(['name' => 'Gerald', '--loud' => true]);
        $loud->assertOutputContains('HELLO GERALD');

        new TestableCommand(new CorgiCommand())->run(['name' => 'Arthur'])->assertExitCode(2);
    }

    public function testRunReturnsResultWithOutputAndExitCode(): void
    {
        $result = new TestableCommand(new CorgiCommand())->run(['name' => 'Rupert']);

        $result->assertSuccessful()->assertOutputContains('Hello Rupert');
    }
}
