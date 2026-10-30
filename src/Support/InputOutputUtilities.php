<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Support;

use Myerscode\Acorn\Foundation\Console\Definition\SignatureInputDefinition;
use Myerscode\Acorn\Foundation\Console\Output\StreamOutput;
use Myerscode\Acorn\Foundation\Console\Output\VoidOutput;
use Myerscode\Acorn\Framework\Console\ConsoleInputInterface;
use Myerscode\Acorn\Testing\Testable\Input;
use Myerscode\Acorn\Testing\Testable\Output;
use RuntimeException;

trait InputOutputUtilities
{
    /**
     * @param array<mixed> $userInput
     */
    public function createInput(array $userInput = [], string $signature = ''): ConsoleInputInterface
    {
        if ($signature !== '') {
            $definition = new SignatureInputDefinition($signature);
        } else {
            $definition = null;
        }

        $input = new Input($userInput, $definition);

        $input->setStream($this->createInputStream($userInput));

        return $input;
    }

    public function createStreamOutput(?ConsoleInputInterface $consoleInput = null): Output
    {
        if (is_null($consoleInput)) {
            $consoleInput = $this->createInput();
        }

        $stream = $this->createStream();

        return new Output($consoleInput, new StreamOutput($stream), $stream);
    }

    public function createVoidOutput(?ConsoleInputInterface $consoleInput = null): Output
    {
        if (is_null($consoleInput)) {
            $consoleInput = $this->createInput();
        }

        $stream = $this->createStream();

        return new Output($consoleInput, new VoidOutput(), $stream);
    }

    /**
     * @param array<mixed> $inputs
     * @return resource
     */
    protected function createInputStream(array $inputs)
    {
        $stream = $this->createStream();

        foreach ($inputs as $input) {
            fwrite($stream, $input . PHP_EOL);
        }

        rewind($stream);

        return $stream;
    }

    /**
     * @return resource
     */
    protected function createStream()
    {
        $stream = fopen('php://memory', 'r+');

        if ($stream === false) {
            throw new RuntimeException('Failed to open php://memory stream');
        }

        return $stream;
    }
}
