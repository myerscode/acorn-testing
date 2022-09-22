<?php

namespace Myerscode\Acorn\Testing;

use Closure;
use Exception;
use Mockery;
use Mockery\LegacyMockInterface;
use Myerscode\Acorn\Application;
use Myerscode\Acorn\Framework\Config\Manager as ConfigManager;
use Myerscode\Acorn\Testing\Interactions\InteractsWithPhpFile;
use Myerscode\Config\Config;
use Myerscode\Utilities\Strings\Utility;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class AcornTestCase extends TestCase
{
    use InteractsWithPhpFile;

    protected string $appDirectory = 'app';

    /**
     * Where is the test suite running from.
     * This should be set in the executing test case, so Acorn can attempt to locate files
     *
     * @return string
     */
    abstract public function runningFrom(): string;

    /**
     * Catch a given exception from closure execution
     *
     * @param string $exceptionClass
     *
     * @return mixed
     */
    public function catch(string $exceptionClass): mixed
    {
        return new class ($exceptionClass) {
            public function __construct(private $exceptionClass)
            {
            }

            public function from(Closure $closure)
            {
                try {
                    return $closure();
                } catch (Exception $exception) {
                    if (!($exception instanceof $this->exceptionClass)) {
                        throw $exception;
                    }
                }
            }
        };
    }

    /**
     * Shortcut for creating a mock class
     */
    public function mock(string $class, array $constructorArgs = []): LegacyMockInterface
    {
        return Mockery::mock($class, $constructorArgs);
    }

    /**
     * Get a fully qualified path to a resource file needed for testing
     *
     * (Resource file should relative to the directoryWithTests path)
     */
    public function resourceFilePath(string $fileName = ''): string
    {
        return $this->path($this->directoryWithTests() . $fileName);
    }

    /**
     * Shortcut for creating a spy class
     */
    public function spy($class, $constructorArgs = []): LegacyMockInterface
    {
        return Mockery::spy($class, $constructorArgs);
    }

    /**
     * Shortcut for creating a stub class
     */
    public function stub($class): LegacyMockInterface
    {
        return Mockery::mock($class);
    }

    /**
     * Path where the test files are located
     */
    public function directoryWithTests(): string
    {
        return $this->runningFrom() . DIRECTORY_SEPARATOR . $this->appDirectory . DIRECTORY_SEPARATOR;
    }

    protected function path(string|Utility $path): string
    {
        return Utility::make($path)->replace(['\\', '/'], DIRECTORY_SEPARATOR)->value();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpTraits();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    protected function setUpTraits(): void
    {
        $traits = $this->getClassTraits(static::class);
    }

    /**
     * Get the base path for the app being tested
     */
    protected function appBase(): string
    {
        return $this->path($this->directoryWithTests());
    }

    /**
     * Get the source directory path for the app being tested
     */
    protected function appSrc(): string
    {
        $reflectionClass = new ReflectionClass(Application::class);

        return $this->path(dirname($reflectionClass->getFileName()));
    }

    /**
     * Get the current working directory for where rhe app is being executed from
     */
    protected function appWorkingDirectory(): string
    {
        return $this->path($this->runningFrom());
    }

    protected function configManager(string $basePath = null): ConfigManager
    {
        return (new ConfigManager($basePath ?? $this->appBase()))
            ->shouldIgnoreCache()
            ->doNotCacheConfig();
    }

    /**
     * Get a configuration object for the testing app
     */
    protected function appConfig(string $basePath = null): Config
    {
        return $this->configManager($basePath)
            ->loadConfig(
                [
                    __DIR__ . '/../vendor/myerscode/acorn-framework/src/Config',
                ],
                [
                    'base' => $this->appBase(),
                    'src' => $this->appSrc(),
                    'cwd' => $this->appWorkingDirectory(),
                ]
            );
    }
}
