<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Foundation\Exceptions\ExceptionHandler;
use Myerscode\Acorn\Framework\Exceptions\ExceptionHandlerInterface;
use Myerscode\Acorn\Framework\Log\NullLogger;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

trait InteractsWithExceptionHandler
{
    /**
     * Create a fresh exception handler for testing.
     */
    public function createExceptionHandler(): ExceptionHandler
    {
        return new ExceptionHandler(new NullLogger());
    }

    /**
     * Render an exception and return the output as a string.
     */
    public function renderException(ExceptionHandlerInterface $handler, Throwable $exception, int $verbosity = OutputInterface::VERBOSITY_NORMAL): string
    {
        $output = new BufferedOutput($verbosity);
        $handler->render($output, $exception);

        return $output->fetch();
    }

    /**
     * Assert that an exception would be reported (not suppressed).
     */
    public function assertWillReport(ExceptionHandlerInterface $handler, Throwable $exception): void
    {
        $this->assertTrue(
            $handler->shouldReport($exception),
            sprintf('Expected %s to be reported, but it is suppressed.', $exception::class),
        );
    }

    /**
     * Assert that an exception would not be reported (is suppressed).
     */
    public function assertWillNotReport(ExceptionHandlerInterface $handler, Throwable $exception): void
    {
        $this->assertFalse(
            $handler->shouldReport($exception),
            sprintf('Expected %s to be suppressed, but it would be reported.', $exception::class),
        );
    }
}
