# Command Assertions

Acorn Testing provides two styles for asserting about a command:

- **`CommandAssertions`** — static, PHPUnit-style assertions.
- **`TestableCommand`** — a fluent, chainable wrapper (what `command()` returns).

Both cover a command's *definition* (name, class, description, arguments,
options, visibility) and its *execution* (exit code).

## PHPUnit-style: `CommandAssertions`

`CommandAssertions` extends `PHPUnit\Framework\Assert`, so its methods are
static, throw `PHPUnit\Framework\AssertionFailedError` on failure, and read like
the native assertions.

```php
use Myerscode\Acorn\Testing\Support\CommandAssertions;

$command = new GreetCommand();

CommandAssertions::assertCommandHasName($command, 'greet');
CommandAssertions::assertCommandHasClassName($command, GreetCommand::class);
CommandAssertions::assertCommandHasDescription($command, 'Greet a corgi.');
CommandAssertions::assertCommandHasArgument($command, 'name');
CommandAssertions::assertCommandHasOption($command, 'loud');
CommandAssertions::assertCommandVisible($command);

// Runs the command and asserts its exit code
CommandAssertions::assertCommandExits($command, 0, ['name' => 'Rupert']);
```

A command resolves by its signature name *or* any alias, including its
fully-qualified class name:

```php
CommandAssertions::assertCommandHasName($command, GreetCommand::class); // passes
```

## Fluent: `TestableCommand`

Obtain a `TestableCommand` from `command()` (or construct one directly). Every
`assert*` method returns `$this` for chaining; the `has*`/`is*` predicates
return booleans for use in your own assertions.

```php
$this->command('greet')
    ->assertHasName('greet')
    ->assertHasClassName(GreetCommand::class)
    ->assertHasDescription('Greet a corgi.')
    ->assertHasArgument('name')
    ->assertHasOption('loud')
    ->assertVisible()
    ->assertExits(0, ['name' => 'Gerald'])
    ->assertExitsSuccessfully(['name' => 'Rupert']);
```

### Predicates

```php
$command = $this->command('greet');

if ($command->hasOption('loud')) {
    // ...
}

$this->assertTrue($command->hasClassName(GreetCommand::class));
$this->assertFalse($command->isHidden());
```

### Running for output assertions

`run()` executes the command and returns a
[`CommandResult`](commands.md#commandresult):

```php
$this->command('greet')
    ->run(['name' => 'Gerald', '--loud' => true])
    ->assertOutputContains('HELLO GERALD');
```

Prompt answers can be supplied as the second argument:

```php
$this->command('setup')->run([], ['yes', 'Rupert']);
```

## Which style should I use?

- Reach for **`TestableCommand`** (via `command()`) in a test case — it is
  concise and chainable.
- Reach for **`CommandAssertions`** when you want static assertions, e.g. in a
  shared assertion helper or a data-driven loop, or to match the look of
  PHPUnit's own `Assert::` calls.
