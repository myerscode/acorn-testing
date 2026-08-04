# Testing Progress Indicators

Use `InteractsWithProgress` to test commands that display progress bars, spinners, or task output.

## Basic Usage

```php
use Myerscode\Acorn\Testing\Interactions\InteractsWithProgress;

class ImportCommandTest extends AcornTestCase
{
    use InteractsWithProgress;

    public function testShowsCompletionMessage(): void
    {
        $result = $this->callAndCaptureOutput('import:users');

        $result->assertSuccessful()
            ->assertOutputContains('Import complete');
    }
}
```

## Testing withTask Output

Commands using `withTask()` show elapsed time:

```php
public function testTaskShowsTime(): void
{
    $result = $this->callAndCaptureOutput('build:assets');

    $result->assertOutputContains('Compiling assets...')
        ->assertOutputMatches('/\(\d+\.?\d*s\)/');
}
```

## Testing withSpinner Output

Commands using `withSpinner()` show a message followed by "done.":

```php
public function testSpinnerOutput(): void
{
    $result = $this->callAndCaptureOutput('fetch:data');

    $result->assertOutputContains('Fetching...')
        ->assertOutputContains('done.');
}
```

## With Arguments

```php
public function testProgressWithArguments(): void
{
    $result = $this->callAndCaptureOutput('process:batch', ['--limit' => '10']);

    $result->assertSuccessful();
}
```
