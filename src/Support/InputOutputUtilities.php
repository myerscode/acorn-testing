<?php

namespace Myerscode\Acorn\Testing\Support;

use Myerscode\Acorn\Foundation\Console\Definition\SignatureInputDefinition;
use Myerscode\Acorn\Foundation\Console\Output\StreamOutput;
use Myerscode\Acorn\Foundation\Console\Output\VoidOutput;
use Myerscode\Acorn\Framework\Console\ConsoleInputInterface;
use Myerscode\Acorn\Testing\Testable\Input;
use Myerscode\Acorn\Testing\Testable\Output;

trait InputOutputUtilities
{
    public function createInput(array $userInput = [], string $signature = ''): ConsoleInputInterface
    {
        if (strlen($signature) > 0) {
            $definition = new SignatureInputDefinition($signature);
        } else {
            $definition = null;
        }

        $input = new Input($userInput, $definition);

        $input->setStream($this->createInputStream($userInput));

        return $input;
    }

    public function createStreamOutput(ConsoleInputInterface $input = null): Output
    {
        if (is_null($input)) {
            $input = $this->createInput();
        }

        $stream = $this->createStream();

        return new Output($input, new StreamOutput($stream), $stream);
    }

    public function createVoidOutput(ConsoleInputInterface $input = null): Output
    {
        if (is_null($input)) {
            $input = $this->createInput();
        }

        $stream = $this->createStream();

        return new Output($input, new VoidOutput(), $stream);
    }

    /**
     * @return resource
     */
    protected function createInputStream(array $inputs)
    {
        $stream = $this->createStream();

        foreach ($inputs as $input) {
            fwrite($stream, $input.PHP_EOL);
        }

        rewind($stream);

        return $stream;
    }

    /**
     * @return resource
     */
    protected function createStream()
    {
        return fopen('php://memory', 'r+');
    }
}
