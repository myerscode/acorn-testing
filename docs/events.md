# Testing Events

Use `InteractsWithDispatcher` to assert that events are dispatched correctly.

## Setting Up the Fake

Call `fakeDispatcher()` before the code under test runs. This swaps in a recording dispatcher:

```php
use Myerscode\Acorn\Testing\Interactions\InteractsWithDispatcher;

class OrderServiceTest extends AcornTestCase
{
    use InteractsWithDispatcher;

    public function testOrderEmitsEvent(): void
    {
        $dispatcher = $this->fakeDispatcher();

        $dispatcher->emit(OrderPlacedEvent::class, ['order_id' => 123]);

        $this->assertDispatched(OrderPlacedEvent::class);
    }
}
```

## Assert Dispatched

Check that an event was fired:

```php
$this->assertDispatched(UserCreatedEvent::class);
```

Assert it was fired a specific number of times:

```php
$this->assertDispatched(UserCreatedEvent::class, times: 2);
```

## Assert Not Dispatched

```php
$this->assertNotDispatched(OrderCancelledEvent::class);
```

## Assert Nothing Dispatched

```php
$this->assertNothingDispatched();
```

## Inspecting Dispatched Events

The `FakeDispatcher` provides direct access to recorded events:

```php
$dispatcher = $this->fakeDispatcher();

// ... trigger some events ...

$all = $dispatcher->dispatched();           // all events
$specific = $dispatcher->dispatchedFor(OrderPlacedEvent::class);
$count = $dispatcher->dispatchedCount(OrderPlacedEvent::class);
$was = $dispatcher->hasDispatched(OrderPlacedEvent::class); // bool
```

## Resetting

Clear recorded events mid-test:

```php
$dispatcher->reset();
```

## Listeners Still Fire

The `FakeDispatcher` extends the real `Dispatcher`, so registered listeners still execute. This makes it useful for integration tests where you want to verify both event emission and listener behaviour.
