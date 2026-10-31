# Testing with Config Overrides

`InteractsWithConfig` overrides config values during tests without affecting
other tests. It is composed into `AcornTestCase`, so you do not need to `use`
it, and overrides are restored automatically in `tearDown()`.

> **App config is loaded automatically.** `AcornTestCase` loads the framework's
> core config *and* your app's own `app/Config` directory, exactly as the
> runtime `Kernel` does. Config your app defines (and the `app.dir.*` paths that
> drive command and listener discovery) is available in tests without manual
> wiring — override only the specific values a test needs.

## Basic Usage

```php
class CacheTest extends AcornTestCase
{
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

`AcornTestCase::tearDown()` calls `restoreConfig()` for you, so overridden
values are reset between tests and nothing leaks. Only values you set via
`setConfig()` or `setConfigValues()` are tracked and restored; values you did
not touch are left alone.

If you compose `InteractsWithConfig` into a plain `PHPUnit\Framework\TestCase`
instead of extending `AcornTestCase`, call `restoreConfig()` in your own
`tearDown()`:

```php
protected function tearDown(): void
{
    $this->restoreConfig();
    parent::tearDown();
}
```
