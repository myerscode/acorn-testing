<?php

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
        if (!(property_exists($this, 'app') && $this->app !== null)) {
            $this->refreshApplication();
        }

        return $this->app;
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

        unset($this->app);
    }

    public function refreshApplication(Container $container = new Container()): Application
    {
        $this->app = $this->createApplication($container);

        return $this->app;
    }
}
