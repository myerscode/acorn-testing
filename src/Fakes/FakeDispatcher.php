<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Fakes;

use Myerscode\Acorn\Foundation\Queue\SynchronousQueue;
use Myerscode\Acorn\Framework\Events\Dispatcher;
use Myerscode\Acorn\Framework\Queue\QueueInterface;

/**
 * A test double for the Dispatcher that records all dispatched/emitted events.
 *
 * Events are still dispatched to listeners (so side-effects happen),
 * but each event is recorded for later assertion.
 */
class FakeDispatcher extends Dispatcher
{
    /** @var list<array{event: string, payload: mixed}> */
    private array $dispatched = [];

    public function __construct(?QueueInterface $queue = null)
    {
        parent::__construct($queue ?? new SynchronousQueue());
    }

    public function dispatch(object $event, ?string $eventName = null): object
    {
        $name = $eventName ?? $event::class;
        $this->dispatched[] = ['event' => $name, 'payload' => $event];

        return parent::dispatch($event, $eventName);
    }

    /**
     * Get all recorded dispatched events.
     *
     * @return list<array{event: string, payload: mixed}>
     */
    public function dispatched(): array
    {
        return $this->dispatched;
    }

    /**
     * Get the number of times an event was dispatched.
     */
    public function dispatchedCount(string $eventName): int
    {
        return count($this->dispatchedFor($eventName));
    }

    /**
     * Get events dispatched matching a given event name or class.
     *
     * @return list<array{event: string, payload: mixed}>
     */
    public function dispatchedFor(string $eventName): array
    {
        return array_values(array_filter(
            $this->dispatched,
            fn (array $entry): bool => $entry['event'] === $eventName,
        ));
    }

    public function emit(string $eventName, mixed $params = null): self
    {
        $this->dispatched[] = ['event' => $eventName, 'payload' => $params];

        parent::emit($eventName, $params);

        return $this;
    }

    /**
     * Check if a given event was dispatched.
     */
    public function hasDispatched(string $eventName): bool
    {
        return $this->dispatchedFor($eventName) !== [];
    }

    /**
     * Clear all recorded events.
     */
    public function reset(): void
    {
        $this->dispatched = [];
    }
}
