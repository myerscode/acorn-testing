# Testing Commands

Use `InteractsWithCommands` to run commands and assert their output.

## Basic Usage

```php
use Myerscode\Acorn\Testing\Interactions\InteractsWithCommands;

class MyCommandTest extends AcornTestCase
{
    use InteractsWithCommands;

    public function testGreetCommand(): void
    {
        $output = $this->call('greet', ['name' => 'Fred']);

        $this->assertSame('Hello Fred', $output);
    }
}
```

## Calling by Class

You can pass a command instance directly:

```php
use App\Commands\GreetCommand;

public function testGreetByClass(): void
{
    $output = $this->call(GreetCommand::class, ['name' => 'Fred']);

    $this->assertSame('Hello Fred', $output);
}
```

## Inline Commands

For isolated tests, define anonymous commands:

```php
use Myerscode\Acorn\Framework\Console\Command;

public function testInlineCommand(): void
{
    $command = new class () extends Command {
        protected string $signature = 'test:inline {name}';

        public function handle(): int
        {
            $this->line('Hi ' . $this->argument('name'));
            return 0;
        }
    };

    $output = $this->call($command, ['name' => 'World']);

    $this->assertSame('Hi World', $output);
}
```
