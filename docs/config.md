# Testing with Config Overrides

Use `InteractsWithConfig` to override config values during tests without affecting other tests.

## Basic Usage

```php
use Myerscode\Acorn\Testing\Interactions\InteractsWithConfig;

class CacheTest extends AcornTestCase
{
    use InteractsWithConfig;

    protected function tearDown(): void
    {
        $this->restoreConfig();
        parent::tearDown();
    }

    public function testUsesFileDriver(): void
    {
        $this->setConfig('app.cache.driver', FileCacheDriver::class);

        // ... code that reads config('app.cache.driver')
    }
}
```

## Bulk Overrides

Set multiple values at once:

```php
public function testCustomErrorConfig(): void
{
    $this->setConfigValues([
        'error.default' => MyCustomHandler::class,
        'error.dont_report' => [ValidationException::class],
    ]);

    // ... test error handling behaviour
}
```

## Reading Config in Assertions

```php
public function testConfigIsSet(): void
{
    $this->setConfig('app.name', 'TestApp');

    $this->assertSame('TestApp', $this->getConfig('app.name'));
}
```

## Automatic Restoration

Call `restoreConfig()` in `tearDown()` to reset all overridden values. This ensures test isolation — config changes in one test don't leak into the next.

```php
protected function tearDown(): void
{
    $this->restoreConfig();
    parent::tearDown();
}
```

Only values you've set via `setConfig()` or `setConfigValues()` are tracked and restored. Values you didn't touch remain unchanged.
