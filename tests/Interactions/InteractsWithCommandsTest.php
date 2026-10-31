<?php

declare(strict_types=1);

namespace Tests\Interactions;

use Tests\BaseTestCase;
use Tests\Fixtures\CorgiCommand;

final class InteractsWithCommandsTest extends BaseTestCase
{
    public function testCallReturnsOutputString(): void
    {
        $output = $this->call(new CorgiCommand(), ['name' => 'Rupert']);

        $this->assertStringContainsString('Hello Rupert', $output);
    }

    public function testCapturedStreamsErrorOutputEmptyForCleanRun(): void
    {
        $this->runCommandCapturingStreams(new CorgiCommand(), ['name' => 'Rupert'])
            ->assertOutputContains('Hello Rupert')
            ->assertErrorOutputEmpty();
    }

    public function testCommandFactoryWrapsAnInstance(): void
    {
        $this->command(new CorgiCommand())
            ->assertHasName('corgi:greet')
            ->assertHasOption('loud');
    }

    public function testRunCommandCapturingStreamsSeparatesStdoutAndStderr(): void
    {
        $result = $this->runCommandCapturingStreams(new CorgiCommand(), ['name' => 'Arthur']);

        // Standard output has the greeting; the warning is on stderr only.
        $result->assertOutputContains('Hello Arthur')
            ->assertOutputMissing('being naughty')
            ->assertErrorOutputContains('Arthur is being naughty')
            ->assertExitCode(2);
    }

    public function testRunCommandReturnsResult(): void
    {
        $this->runCommand(new CorgiCommand(), ['name' => 'Gerald'])
            ->assertSuccessful()
            ->assertOutputContains('Hello Gerald');
    }
}
