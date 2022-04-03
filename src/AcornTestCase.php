<?php

namespace Myerscode\Acorn\Testing;

use Mockery;
use Mockery\LegacyMockInterface;
use Myerscode\Acorn\Container;
use Myerscode\Acorn\Framework\Events\CallableEventManager;
use Myerscode\Utilities\Strings\Utility;
use PHPUnit\Framework\TestCase;

abstract class AcornTestCase extends TestCase
{
    use InteractsWithApplication;

    protected string $testDirectory = 'tests';

    public function mock($class, $constructorArgs = []): LegacyMockInterface
    {
        return Mockery::mock($class, $constructorArgs);
    }

    public function spy($class, $constructorArgs = []): LegacyMockInterface
    {
        return Mockery::spy($class, $constructorArgs);
    }

    public function stub($class): LegacyMockInterface
    {
        return Mockery::mock($class);
    }

    protected function setUp(): void
    {
        if (!isset(self::$app)) {
            $this->bootstrap();
        }

        $this->setUpTraits();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        CallableEventManager::clear();
        Container::flush();
        Mockery::close();
//        $this->destroy();
    }

    protected function setUpTraits()
    {
        $traits = get_declared_traits();

        if (in_array(RefreshApp::class, $traits)) {
            $this->bootstrap();
        }

        if (in_array(RefreshContainer::class, $traits)) {
            $this->refreshContainer();
        }
    }

    abstract public function runningFrom(): string;

    public function theTestDirectory(): string
    {
        return $this->runningFrom() . $this->testDirectory . DIRECTORY_SEPARATOR;
    }

    public function resourceFilePath($fileName = ''): string
    {
        return $this->path($this->theTestDirectory() . $fileName);
    }

    protected function path($path): string
    {
        return Utility::make($path)->replace(['\\', '/'], DIRECTORY_SEPARATOR)->value();
    }

    public function catch($e): object
    {
        return new class ($e) {
            public function __construct(private $e)
            {
            }

            public function from(\Closure $c)
            {
                try {
                    return $c();
                } catch (\Exception $exception) {
                    if (!($exception instanceof $this->e)) {
                        throw $exception;
                    }
                }
            }
        };
    }
}
