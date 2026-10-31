<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Testable;

use RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * A ConsoleOutputInterface backed by two in-memory streams, one for stdout
 * and one for stderr.
 *
 * Because it is a real ConsoleOutputInterface, SymfonyStyle::getErrorStyle()
 * (and anything that writes via getErrorOutput()) is routed to the separate
 * error stream, so tests can assert on standard and error output independently
 * instead of seeing them merged.
 */
final class CapturingConsoleOutput extends StreamOutput implements ConsoleOutputInterface
{
    /** @var ConsoleSectionOutput[] */
    private array $consoleSectionOutputs = [];
    private readonly StreamOutput $stderr;

    public function __construct(
        int $verbosity = self::VERBOSITY_NORMAL,
        ?bool $decorated = false,
        ?OutputFormatterInterface $formatter = null,
    ) {
        parent::__construct($this->memoryStream(), $verbosity, $decorated, $formatter);

        $this->stderr = new StreamOutput($this->memoryStream(), $verbosity, $decorated, $this->getFormatter());
    }

    /**
     * Captured error output.
     */
    public function fetchErrorOutput(): string
    {
        return $this->readStream($this->stderr->getStream());
    }

    /**
     * Captured standard output.
     */
    public function fetchOutput(): string
    {
        return $this->readStream($this->getStream());
    }

    public function getErrorOutput(): OutputInterface
    {
        return $this->stderr;
    }

    public function section(): ConsoleSectionOutput
    {
        return new ConsoleSectionOutput(
            $this->getStream(),
            $this->consoleSectionOutputs,
            $this->getVerbosity(),
            $this->isDecorated(),
            $this->getFormatter(),
        );
    }

    public function setErrorOutput(OutputInterface $error): void
    {
        // The capturing output owns its error stream; swapping it out would
        // defeat the point, so this is intentionally a no-op.
    }

    /**
     * @return resource
     */
    private function memoryStream()
    {
        $stream = fopen('php://memory', 'r+');

        if ($stream === false) {
            throw new RuntimeException('Failed to open php://memory stream');
        }

        return $stream;
    }

    /**
     * @param resource $stream
     */
    private function readStream($stream): string
    {
        rewind($stream);

        return trim(str_replace(PHP_EOL, "\n", (string) stream_get_contents($stream)));
    }
}
