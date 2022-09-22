<?php

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Application;
use Myerscode\Acorn\Framework\Container\Container;

use function Myerscode\Acorn\Foundation\config;

trait InteractsWithContainer
{
    protected ?Container $container = null;

    /**
     * Get current Container in test
     */
    public function container(): Container
    {
        if (!(property_exists($this, 'container') && $this->container !== null)) {
            $this->refreshContainer();
        }

        return $this->container;
    }

    public function newContainer(): Container
    {
        $config = $this->appConfig();
        $container = new Container();
        $container->add('config', $config);

        foreach ($config->value('framework.providers', []) as $provider) {
            $container->addServiceProvider($provider);
        }

        return $container;
    }

    public function refreshContainer(): Container
    {
        if (property_exists($this, 'container') && $this->container !== null) {
            $this->container->flush();
        }

        $this->container = $this->newContainer();

        return $this->container;
    }
}
