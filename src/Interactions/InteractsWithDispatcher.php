<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Foundation\Queue\SynchronousQueue;
use Myerscode\Acorn\Framework\Events\Dispatcher;
use Myerscode\Acorn\Framework\Queue\QueueInterface;
use Myerscode\Acorn\Testing\Fakes\FakeDispatcher;
use Myerscode\Acorn\Testing\Support\Assertions;

trait InteractsWithDispatcher
{
    protected ?Dispatcher $dispatcher = null;

    /**
     * Assert that an event was dispatched.
     */
    public function assertDispatched(string $eventName, ?int $times = null): void
    {
        $fake = $this->getFakeDispatcher();

        Assertions::assertTrue(
            $fake->hasDispatched($eventName),
            sprintf('Expected event [%s] to be dispatched, but it was not.', $eventName),
        );

        if ($times !== null) {
            $actual = $fake->dispatchedCount($eventName);
            Assertions::assertSame(
                $times,
                $actual,
                sprintf('Expected event [%s] to be dispatched %d time(s), but was dispatched %d time(s).', $eventName, $times, $actual),
            );
        }
    }

    /**
     * Assert that an event was not dispatched.
     */
    public function assertNotDispatched(string $eventName): void
    {
        $fake = $this->getFakeDispatcher();

        Assertions::assertFalse(
            $fake->hasDispatched($eventName),
            sprintf('Expected event [%s] not to be dispatched, but it was.', $eventName),
        );
    }

    /**
     * Assert that no events were dispatched at all.
     */
    public function assertNothingDispatched(): void
    {
        $fake = $this->getFakeDispatcher();

        Assertions::assertSame(
            [],
            $fake->dispatched(),
            sprintf('Expected no events to be dispatched, but %d were.', count($fake->dispatched())),
        );
    }

    /**
     * Get current Dispatcher in test.
     */
    public function dispatcher(QueueInterface $queue = new SynchronousQueue()): Dispatcher
    {
        if (!isset($this->dispatcher)) {
            $this->refreshDispatcher($queue);
        }

        return $this->dispatcher;
    }

    /**
     * Create a FakeDispatcher for recording and asserting events.
     */
    public function fakeDispatcher(): FakeDispatcher
    {
        $fake = new FakeDispatcher();
        $this->dispatcher = $fake;

        return $fake;
    }

    public function newDispatcher(QueueInterface $queue = new SynchronousQueue()): Dispatcher
    {
        return new Dispatcher($queue);
    }

    public function refreshDispatcher(QueueInterface $queue = new SynchronousQueue()): Dispatcher
    {
        $this->dispatcher = $this->newDispatcher($queue);

        return $this->dispatcher;
    }

    private function getFakeDispatcher(): FakeDispatcher
    {
        if (!$this->dispatcher instanceof FakeDispatcher) {
            Assertions::fail('Event assertions require a FakeDispatcher. Call $this->fakeDispatcher() before dispatching events.');
        }

        return $this->dispatcher;
    }
}
