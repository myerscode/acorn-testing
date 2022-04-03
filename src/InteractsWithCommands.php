<?php

namespace Myerscode\Acorn\Testing;

use Myerscode\Acorn\Foundation\Console\ConfigInput;
use Myerscode\Acorn\Foundation\Console\StreamOutput;
use Myerscode\Acorn\Foundation\Console\VoidOutput;
use Myerscode\Acorn\Framework\Console\Command;

trait InteractsWithCommands
{
    /**
     * @return resource
     */
    protected function createStream(array $inputs)
    {
        $stream = fopen('php://memory', 'r+', false);

        foreach ($inputs as $input) {
            fwrite($stream, $input . PHP_EOL);
        }

        rewind($stream);

        return $stream;
    }

    public function call(string|Command $command, array $userInput = []): string
    {
        $input = new ConfigInput($userInput);

        $input->setStream($this->createStream($input->getArguments()));

        $voidOutput = new VoidOutput();

        $stream = fopen('php://memory', 'w', false);

        $output = new StreamOutput($input, $voidOutput, $stream);

        if (is_string($command)) {
            $command = $this->application()->find($command);
        } else {
            $this->application()->add($command);
        }

        $command->run($input, $output);

        rewind($stream);

        $display = stream_get_contents($stream);

        return trim(str_replace(PHP_EOL, "\n", $display));
    }
}
