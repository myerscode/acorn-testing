# Testing Error Handling

Use `InteractsWithExceptionHandler` to test exception handler configuration and rendering.

## Creating a Handler

```php
use Myerscode\Acorn\Testing\Interactions\InteractsWithExceptionHandler;

class ErrorHandlerTest extends AcornTestCase
{
    use InteractsWithExceptionHandler;

    public function testDefaultHandlerRendersException(): void
    {
        $handler = $this->createExceptionHandler();

        $output = $this->renderException($handler, new \RuntimeException('Something broke'));

        $this->assertStringContainsString('Something broke', $output);
    }
}
```

## Assert Will Report

Check that an exception will be logged:

```php
public function testReportsUnexpectedErrors(): void
{
    $handler = $this->createExceptionHandler();

    $this->assertWillReport($handler, new \RuntimeException('unexpected'));
}
```

## Assert Will Not Report

Check that an exception is suppressed:

```php
use App\Exceptions\ValidationException;

public function testValidationErrorsNotReported(): void
{
    $handler = $this->createExceptionHandler();
    $handler->dontReport(ValidationException::class);

    $this->assertWillNotReport($handler, new ValidationException('invalid'));
}
```

## Testing Custom Handlers

Test your `ErrorHandler` classes directly:

```php
use App\Handlers\NotifyOpsHandler;
use Symfony\Component\Console\Output\BufferedOutput;

public function testNotifyOpsHandlerRendersMessage(): void
{
    $handler = new NotifyOpsHandler($this->mockAlertService);
    $output = new BufferedOutput();

    $result = $handler->handle(new PaymentFailedException('card declined'), $output);

    $this->assertTrue($result);
    $this->assertStringContainsString('Ops notified', $output->fetch());
}
```

## Verbose Output

Test rendering at different verbosity levels:

```php
use Symfony\Component\Console\Output\OutputInterface;

public function testVerboseShowsFileAndLine(): void
{
    $handler = $this->createExceptionHandler();

    $output = $this->renderException(
        $handler,
        new \RuntimeException('test'),
        OutputInterface::VERBOSITY_VERBOSE,
    );

    $this->assertStringContainsString('.php', $output);
}
```
