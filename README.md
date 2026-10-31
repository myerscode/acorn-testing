[![Latest Stable Version](https://poser.pugx.org/myerscode/acorn-testing/v/stable)](https://packagist.org/packages/myerscode/acorn-testing)
[![Total Downloads](https://poser.pugx.org/myerscode/acorn-testing/downloads)](https://packagist.org/packages/myerscode/acorn-testing)
[![PHP Version Require](http://poser.pugx.org/myerscode/acorn-testing/require/php)](https://packagist.org/packages/myerscode/acorn-testing)
[![License](https://poser.pugx.org/myerscode/acorn-testing/license)](https://github.com/myerscode/acorn-testing/blob/main/LICENSE)
[![Tests](https://github.com/myerscode/acorn-testing/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/myerscode/acorn-testing/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/myerscode/acorn-testing/graph/badge.svg)](https://codecov.io/gh/myerscode/acorn-testing)

# Acorn Testing Framework

> Easily test [Acorn](https://github.com/myerscode/acorn-framework) CLI apps.

Acorn Testing gives you a PHPUnit base case and a set of focused helpers for
driving commands, asserting on their output and exit codes, inspecting their
definitions, faking events, and overriding config and environment — all with
test isolation handled for you.

## Requirements

- PHP 8.5 or higher

## Installation

```bash
composer require --dev myerscode/acorn-testing
```

## Quick start

Extend `AcornTestCase` and give it a `runningFrom()` pointing at your project
root. The common interaction helpers are already composed in, so `call()`,
config, event, environment and command helpers are available with no extra
`use` statements, and any config or environment overrides are restored
automatically in `tearDown()`.

```php
use Myerscode\Acorn\Testing\AcornTestCase;

abstract class TestCase extends AcornTestCase
{
    public function runningFrom(): string
    {
        return dirname(__DIR__); // project root (contains the `app` directory)
    }
}
```

```php
final class GreetCommandTest extends TestCase
{
    public function testItGreets(): void
    {
        // call() returns the captured output as a string
        $this->assertStringContainsString('Hello Rupert', $this->call('greet', ['name' => 'Rupert']));

        // runCommand() returns a rich, fluent result
        $this->runCommand('greet', ['name' => 'Gerald'])
            ->assertSuccessful()
            ->assertOutputContains('Hello Gerald');

        // command() inspects and exercises the command definition
        $this->command('greet')
            ->assertHasName('greet')
            ->assertHasArgument('name')
            ->assertExits(0, ['name' => 'Arthur']);
    }
}
```

## Helpers

| Helper | What it does |
| --- | --- |
| [`InteractsWithCommands`](docs/commands.md) | Run commands (`call`, `runCommand`, `runCommandCapturingStreams`) and inspect them (`command`) |
| [`CommandAssertions` / `TestableCommand`](docs/command-assertions.md) | PHPUnit-style and fluent assertions about a command's name, class, options, exit code |
| [`InteractsWithConfig`](docs/config.md) | Override config values with automatic restoration |
| [`InteractsWithEnvironment`](docs/environment.md) | Override environment variables with automatic restoration |
| [`InteractsWithDispatcher`](docs/events.md) | Fake the dispatcher and assert on dispatched events |
| [`InteractsWithPrompts` / `InteractsWithProgress`](docs/prompts.md) | Drive interactive prompts and capture progress output |
| [`InteractsWithExceptionHandler`](docs/error-handling.md) | Assert how a command handles thrown exceptions |
| [`InteractsWithContainer`](docs/container.md) / [`InteractsWithApplication`](docs/application.md) | Access the test container and application |

## Issues

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/myerscode/acorn-testing/issues).

## Contributing

Contributions are welcome! Please see [contributing.md](https://github.com/myerscode/docs/blob/master/contributing.md) for guidelines on how to contribute to this project.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
