# Testing with Environment Overrides

`InteractsWithEnvironment` overrides environment variables for the duration of
a test and restores them afterwards. It is composed into `AcornTestCase`, so it
is available without a `use`, and `restoreEnvironment()` runs automatically in
`tearDown()`.

## Overriding a variable

```php
public function testReadsApiHostFromEnv(): void
{
    $this->setEnvironment('ACORN_API_HOST', 'https://rupert.test');

    $this->assertSame('https://rupert.test', getenv('ACORN_API_HOST'));
    $this->assertSame('https://rupert.test', $this->getEnvironment('ACORN_API_HOST'));
}
```

`setEnvironment()` updates `putenv()`, `$_ENV` and `$_SERVER` so the value is
visible however the code under test reads it.

## Overriding several at once

```php
$this->setEnvironmentValues([
    'ACORN_API_HOST' => 'https://gerald.test',
    'ACORN_API_TOKEN' => 'secret',
]);
```

## Automatic restoration

You do not need to clean up. `AcornTestCase::tearDown()` calls
`restoreEnvironment()`, which returns every overridden variable to its original
value and *unsets* any variable that did not exist before the test — so nothing
leaks into the next test.

If you are composing the trait into a plain `PHPUnit\Framework\TestCase` rather
than extending `AcornTestCase`, call `restoreEnvironment()` in your own
`tearDown()`.

```php
protected function tearDown(): void
{
    $this->restoreEnvironment();
    parent::tearDown();
}
```
