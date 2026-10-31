<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Application;
use Myerscode\Acorn\Framework\Container\Container;

trait InteractsWithApplication
{
    protected ?Application $app = null;

    /**
     * Get current Application in test
     */
    public function application(): Application
    {
        return $this->app ??= $this->createApplication();
    }

    public function createApplication(Container $container = new Container()): Application
    {
        $container->add('config', $this->appConfig());

        return new Application($container);
    }

    /**
     * Destroy the app in test instance
     */
    public function destroy(): void
    {
        $this->application()->reset();

        $this->app = null;
    }

    public function refreshApplication(Container $container = new Container()): Application
    {
        $this->app = $this->createApplication($container);

        return $this->app;
    }
}
