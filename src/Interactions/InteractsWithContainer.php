<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Framework\Container\Container;

trait InteractsWithContainer
{
    protected ?Container $container = null;

    /**
     * Get current Container in test
     */
    public function container(): Container
    {
        return $this->container ??= $this->newContainer();
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
        if ($this->container !== null) {
            $this->container->flush();
        }

        return $this->container = $this->newContainer();
    }
}
