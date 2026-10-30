# Testing Prompts

Use `InteractsWithPrompts` to test commands that ask for user input.

## Basic Usage

```php
use Myerscode\Acorn\Testing\Interactions\InteractsWithPrompts;

class SetupCommandTest extends AcornTestCase
{
    use InteractsWithPrompts;

    public function testAsksForName(): void
    {
        $result = $this->callWithPrompts('setup', ['Fred']);

        $result->assertSuccessful()
            ->assertOutputContains('Hello Fred');
    }
}
```

## Multiple Prompts

Answers are consumed in order, matching the sequence of prompts:

```php
public function testMultiplePrompts(): void
{
    // Command asks: name, email, confirm
    $result = $this->callWithPrompts('user:create', [
        'Fred',
        'fred@example.com',
        'yes',
    ]);

    $result->assertSuccessful()
        ->assertOutputContains('User created');
}
```

## With Arguments

Pass command arguments as the third parameter:

```php
public function testWithArguments(): void
{
    $result = $this->callWithPrompts(
        'deploy',
        ['yes'],                    // prompt answers
        ['--env' => 'staging'],     // command arguments
    );

    $result->assertSuccessful();
}
```

## CommandResult Assertions

The returned `CommandResult` supports fluent assertions:

```php
$result->assertSuccessful();
$result->assertFailed();
$result->assertExitCode(1);
$result->assertOutputContains('text');
$result->assertOutputMissing('secret');
$result->assertOutputEquals('exact output');
$result->assertOutputEmpty();
$result->assertOutputMatches('/pattern/');
```
