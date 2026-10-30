# Testing with the Container

Use `InteractsWithContainer` to access and manipulate the DI container in tests.

## Basic Usage

```php
use Myerscode\Acorn\Testing\Interactions\InteractsWithContainer;

class ServiceTest extends AcornTestCase
{
    use InteractsWithContainer;

    public function testServiceIsRegistered(): void
    {
        $service = $this->container()->get(MyService::class);

        $this->assertInstanceOf(MyService::class, $service);
    }
}
```

## Swapping Bindings

Replace a binding for the duration of a test:

```php
public function testWithMockedService(): void
{
    $mock = $this->mock(PaymentGateway::class);
    $mock->shouldReceive('charge')->andReturn(true);

    $this->container()->swap(PaymentGateway::class, $mock);

    // ... code that resolves PaymentGateway from the container
}
```

## Testing Service Providers

Verify that a provider registers expected services:

```php
public function testProviderRegistersServices(): void
{
    $cache = $this->container()->get(CacheInterface::class);

    $this->assertInstanceOf(Cache::class, $cache);
}
```
