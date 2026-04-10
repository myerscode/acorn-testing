<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Testable;

use Myerscode\Acorn\Foundation\Console\Display\DisplayOutput as AcornConsoleOutput;
use Myerscode\Acorn\Testing\Support\Assertions;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Output extends AcornConsoleOutput
{
    /** @var resource|null */
    protected mixed $stream = null;

    public function __construct(InputInterface $input, OutputInterface $output, mixed $stream = null)
    {
        $this->setStream($stream);

        parent::__construct($input, $output);
    }

    /**
     * @param string|array<string> $expected
     */
    public function assertOutputEquals(string|array $expected): self
    {
        Assertions::assertEquals(implode(PHP_EOL, $this->decorateLines($expected)), $this->output());

        return $this;
    }

    /** @return resource|null */
    public function getStream(): mixed
    {
        return $this->stream;
    }

    public function output(): string
    {
        $stream = $this->stream;

        if ($stream === null) {
            return '';
        }

        rewind($stream);

        $display = stream_get_contents($stream);

        return trim(str_replace(PHP_EOL, "\n", (string) $display));
    }

    public function reset(mixed $stream = null): void
    {
        $this->setStream($stream);
    }

    public function setStream(mixed $stream = null): void
    {
        if (is_null($stream)) {
            $this->stream = fopen('php://memory', 'w') ?: null;
        } else {
            $this->stream = $stream;
        }
    }
}
