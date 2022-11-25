<?php

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Framework\Console\Command;

trait InteractsWithCommands
{
    use InteractsWithApplication;

    public function call(string|Command $command, array $userInput = []): string
    {
        $input = $this->createInput($userInput);

        $output = $this->createStreamOutput($input);

        if (is_string($command)) {
            $command = $this->application()->find($command);
        } else {
            $this->application()->add($command);
        }

        $command->run($input, $output);

        rewind($output->getStream());

        $display = stream_get_contents($output->getStream());

        return trim(str_replace(PHP_EOL, "\n", $display));
    }
}
