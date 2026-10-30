# Testing with the Application

Use `InteractsWithApplication` to boot and access the Acorn application in tests.

## Basic Usage

```php
use Myerscode\Acorn\Testing\Interactions\InteractsWithApplication;

class AppTest extends AcornTestCase
{
    use InteractsWithApplication;

    public function testApplicationBoots(): void
    {
        $app = $this->application();

        $this->assertNotNull($app);
    }
}
```

## Testing Commands are Loaded

```php
public function testCommandsDiscovered(): void
{
    $app = $this->application();

    $this->assertTrue($app->has('greet'));
}
```

## Testing Service Providers are Registered

```php
public function testProvidersLoaded(): void
{
    $providers = $this->application()->loadedServiceProviders();

    $this->assertContains(CacheProvider::class, $providers);
}
```

## Custom App Directory

Override `$appDirectory` in your test case to point at test fixtures:

```php
class MyTest extends AcornTestCase
{
    protected string $appDirectory = 'tests/Fixtures/App';

    public function runningFrom(): string
    {
        return dirname(__DIR__);
    }
}
```
