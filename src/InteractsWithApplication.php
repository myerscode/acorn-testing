<?php

namespace Myerscode\Acorn\Testing;

use Myerscode\Acorn\Application;
use Myerscode\Acorn\Container;
use Myerscode\Acorn\Foundation\Queue\SynchronousQueue;
use Myerscode\Acorn\Framework\Config\Factory as ConfigFactory;
use Myerscode\Acorn\Framework\Events\Dispatcher;
use Myerscode\Acorn\Framework\Queue\QueueInterface;
use Myerscode\Config\Config;
use ReflectionClass;

trait InteractsWithApplication
{
    static protected ?Application $app;

     protected ?Container $container;

     protected ?Dispatcher $dispatcher;

    /**
     * Get the base path for the app being tested
     *
     * @return string
     */
    protected function appBase(): string
    {
        return $this->path($this->theTestDirectory() . 'Resources/App');
    }

    /**
     * Get the source directory path for the app being tested
     *
     * @return string
     */
    protected function appSrc(): string
    {
        $a = new ReflectionClass(Application::class);
        return $this->path(dirname($a->getFileName()));
    }

    /**
     * Get the current working directory for where rhe app is being exectued from
     *
     * @return string
     */
    protected function appWorkingDirectory(): string
    {
        return $this->path( $this->runningFrom() );
    }

    /**
     * Get a configuration object for the testing app
     *
     * @return Config
     */
    protected function appConfig(): Config
    {
        return ConfigFactory::make([
            'base' => $this->appBase(),
            'src' => $this->appSrc(),
            'cwd' => $this->appWorkingDirectory(),
        ]);
    }

    /**
     * Boostrap n usable instance of the acorn app to be user for testing
     *
     * @return void
     */
    public function bootstrap(): void
    {
        $this->refreshContainer();
        $this->refreshDispatcher();
        $this->refreshApplication();
    }

    /**
     * Destroy the app in test instance
     *
     * @return void
     */
    public function destroy(): void
    {
        $this->application()->reset();
        self::$app= null;
//        $this->dispatcher = null;
//        $this->container = null;
//        unset($this->app, $this->dispatcher, $this->container );
    }

    public function createApplication(): Application
    {
        return new Application($this->container(), $this->dispatcher());
    }

    public function refreshApplication(): Application
    {
        self::$app = $this->createApplication();

        return self::$app;
    }

    /**
     * Get current Application in test
     *
     * @return Application
     */
    public function application(): Application
    {
        return self::$app;
    }

    public function newContainer(): Container
    {
        $container = new Container();
        $container->add('config', $this->appConfig());

        return $container;
    }

    public function refreshContainer(): Container
    {
        $this->container = $this->newContainer();
        return $this->container;
    }

    /**
     * Get current Container in test
     *
     * @return Container
     */
    public function container(): Container
    {
        if (!isset($this->container)) {
            $this->refreshContainer();
        }
        return $this->container;
    }

    public function newDispatcher(QueueInterface $queue = new SynchronousQueue()): Dispatcher
    {
        return new Dispatcher($queue);
    }

    public function refreshDispatcher(QueueInterface $queue = new SynchronousQueue()): Dispatcher
    {
        $this->dispatcher = $this->newDispatcher($queue);
        return $this->dispatcher;
    }

    /**
     * Get current Dispatcher in test
     * @return Dispatcher
     */
    public function dispatcher(QueueInterface $queue = new SynchronousQueue()): Dispatcher
    {
        if (!isset($this->dispatcher)) {
            $this->refreshDispatcher();
        }
        return $this->dispatcher;
    }
}
