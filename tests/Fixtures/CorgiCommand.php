<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Myerscode\Acorn\Framework\Console\Command;
use Override;

/**
 * A fixture command for exercising the command-testing helpers.
 */
final class CorgiCommand extends Command
{
    #[Override]
    protected string $description = 'Greets a corgi.';

    #[Override]
    protected string $signature = 'corgi:greet
                                   {name : The corgi to greet}
                                   {--loud : Shout the greeting}';

    #[Override]
    public function handle(): int
    {
        $name = $this->argument('name');
        $greeting = 'Hello ' . (is_string($name) ? $name : 'corgi');

        if ($this->flag('loud')) {
            $greeting = strtoupper($greeting);
        }

        $this->output->writeln($greeting);

        // Exit non-zero for the reserved troublemaker, to exercise exit-code
        // assertions.
        return $name === 'Arthur' ? 2 : 0;
    }
}
