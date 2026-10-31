<?php

declare(strict_types=1);

namespace Tests\Fakes;

use Myerscode\Acorn\Framework\Events\NamedEvent;
use Myerscode\Acorn\Testing\Fakes\FakeDispatcher;
use PHPUnit\Framework\TestCase;

final class FakeDispatcherTest extends TestCase
{
    public function testCountsMultipleEmissionsOfTheSameEvent(): void
    {
        $fake = new FakeDispatcher();

        $fake->emit('arthur.fed');
        $fake->emit('arthur.fed');
        $fake->emit('arthur.fed');

        $this->assertSame(3, $fake->dispatchedCount('arthur.fed'));
    }

    public function testDispatchRecordsUnderTheEventClass(): void
    {
        $fake = new FakeDispatcher();

        $event = new NamedEvent('gerald.napped');
        $fake->dispatch($event);

        $this->assertSame(1, $fake->dispatchedCount(NamedEvent::class));
        $this->assertCount(1, $fake->dispatched());
    }

    public function testEmitPreservesTheNamedEventName(): void
    {
        $fake = new FakeDispatcher();

        $fake->emit('corgi.barked', 'Rupert');

        // The recorded name is the caller's name, not NamedEvent::class.
        $this->assertTrue($fake->hasDispatched('corgi.barked'));
        $this->assertFalse($fake->hasDispatched(NamedEvent::class));

        // The payload is the event object emit() constructed for that name.
        $entry = $fake->dispatchedFor('corgi.barked')[0];
        $this->assertInstanceOf(NamedEvent::class, $entry['payload']);
        $this->assertSame('corgi.barked', $entry['payload']->eventName());
    }
    public function testEmitRecordsEachEventExactlyOnce(): void
    {
        $fake = new FakeDispatcher();

        // Regression: emit() used to record once itself and once more via the
        // dispatch() it delegates to, double-counting every emitted event.
        $fake->emit('corgi.barked');

        $this->assertCount(1, $fake->dispatched());
        $this->assertSame(1, $fake->dispatchedCount('corgi.barked'));
    }

    public function testHasDispatchedIsFalseForUnseenEvent(): void
    {
        $fake = new FakeDispatcher();

        $fake->emit('rupert.walked');

        $this->assertTrue($fake->hasDispatched('rupert.walked'));
        $this->assertFalse($fake->hasDispatched('gerald.walked'));
    }

    public function testResetClearsRecordedEvents(): void
    {
        $fake = new FakeDispatcher();

        $fake->emit('corgi.barked');
        $this->assertNotEmpty($fake->dispatched());

        $fake->reset();

        $this->assertSame([], $fake->dispatched());
        $this->assertFalse($fake->hasDispatched('corgi.barked'));
    }
}
