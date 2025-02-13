<?php

namespace Myerscode\Acorn\Testing\Testable;

use Myerscode\Acorn\Testing\Support\Assertions;
use Symfony\Component\Console\Input\InputInterface;
use Myerscode\Acorn\Foundation\Console\Display\DisplayOutput as AcornConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Output extends AcornConsoleOutput
{
    public function __construct(InputInterface $input, OutputInterface $output, protected mixed $stream = null)
    {
        $this->setStream($this->stream);

        parent::__construct($input, $output);
    }

    public function assertOutputEquals(string|array $expected): self
    {
        Assertions::assertEquals(implode(PHP_EOL, $this->decorateLines($expected)), $this->output());

        return $this;
    }

    public function getStream()
    {
        return $this->stream;
    }

    public function output(): string
    {
        $steam = $this->stream;

        rewind($steam);

        $display = stream_get_contents($steam);

        return trim(str_replace(PHP_EOL, "\n", $display));
    }

    public function reset(mixed $stream = null): void
    {
        $this->setStream($stream);
    }

    public function setStream(mixed $stream = null): void
    {
        if (is_null($stream)) {
            $this->stream = fopen('php://memory', 'w');
        }
    }
}
