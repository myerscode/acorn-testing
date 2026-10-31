# Testing Commands

`InteractsWithCommands` runs commands and inspects them. It is composed into
`AcornTestCase`, so you do not need to `use` it yourself.

## Running a command

### `call()` — output as a string

`call()` runs a command and returns its captured standard output as a string.
This is the simplest option and works directly with PHPUnit's string
assertions.

```php
public function testGreetCommand(): void
{
    $output = $this->call('greet', ['name' => 'Rupert']);

    $this->assertSame('Hello Rupert', $output);
}
```

### `runCommand()` — a rich result

`runCommand()` returns a [`CommandResult`](#commandresult) carrying both the
output and the exit code, with a fluent assertion API.

```php
public function testGreetSucceeds(): void
{
    $this->runCommand('greet', ['name' => 'Gerald'])
        ->assertSuccessful()
        ->assertOutputContains('Hello Gerald');
}
```

### Calling by class or instance

Pass a fully-qualified class name, or a command instance for isolated tests.

```php
use App\Commands\GreetCommand;

$this->call(GreetCommand::class, ['name' => 'Rupert']);

$command = new class () extends Command {
    protected string $signature = 'test:inline {name}';

    public function handle(): int
    {
        $this->line('Hi ' . $this->argument('name'));

        return 0;
    }
};

$this->assertSame('Hi Arthur', $this->call($command, ['name' => 'Arthur']));
```

## `CommandResult`

`runCommand()` (and the prompt/progress helpers) return a `CommandResult`. It
exposes `output`, `exitCode` and `errorOutput` as readonly properties and a
chainable assertion API. It is also `Stringable`, yielding the output.

```php
$result = $this->runCommand('greet', ['name' => 'Gerald']);

$result
    ->assertSuccessful()                 // exit code 0
    ->assertExitCode(0)
    ->assertOutputContains('Hello')
    ->assertOutputEquals('Hello Gerald')
    ->assertOutputMatches('/Hello \w+/')
    ->assertOutputMissing('Goodbye');

$this->runCommand('greet', ['name' => 'Arthur'])->assertFailed();
```

## Capturing standard and error output separately

By default output is merged, as it is on a normal terminal. When you need to
assert on error output independently, use `runCommandCapturingStreams()`: output
written via the command's error style (`getErrorStyle()` / stderr) is captured
into `errorOutput`, everything else into `output`.

```php
public function testWarnsOnStdErr(): void
{
    $result = $this->runCommandCapturingStreams('deploy', ['--force' => true]);

    $result
        ->assertSuccessful()
        ->assertOutputContains('Deployed')
        ->assertErrorOutputContains('using --force')
        ->assertOutputMissing('using --force'); // the warning is on stderr only
}
```

## Inspecting a command

`command()` wraps a command (by name, class, or instance) in a
[`TestableCommand`](command-assertions.md) for fluent assertions about its
definition and behaviour.

```php
$this->command('make:command')
    ->assertHasName('make:command')
    ->assertHasClassName(MakeCommandCommand::class)
    ->assertHasArgument('name')
    ->assertHasOption('force')
    ->assertVisible()
    ->assertExits(0, ['name' => 'Rupert']);
```

See [Command assertions](command-assertions.md) for the full API, including the
PHPUnit-style static `CommandAssertions` class.
