<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Interactions;

use Myerscode\Acorn\Framework\Container\Container;
use Myerscode\Config\Config;

trait InteractsWithConfig
{
    /** @var array<string, mixed> */
    private array $originalConfigValues = [];

    /**
     * Get a config value (useful for asserting config state).
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        return $this->getConfigInstance()->store()->get($key, $default);
    }

    /**
     * Restore all overridden config values to their original state.
     *
     * Call this in tearDown() if you use setConfig() in your tests.
     */
    public function restoreConfig(): void
    {
        $config = $this->getConfigInstance();

        foreach ($this->originalConfigValues as $key => $value) {
            $config->store()->set($key, $value);
        }

        $this->originalConfigValues = [];
    }

    /**
     * Override a config value for the duration of the test.
     *
     * Values are automatically restored in tearDown if you call restoreConfig().
     */
    public function setConfig(string $key, mixed $value): void
    {
        $config = $this->getConfigInstance();

        // Store original value for restoration
        if (!array_key_exists($key, $this->originalConfigValues)) {
            $this->originalConfigValues[$key] = $config->store()->get($key);
        }

        $config->store()->set($key, $value);
    }

    /**
     * Override multiple config values at once.
     *
     * @param array<string, mixed> $values
     */
    public function setConfigValues(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->setConfig($key, $value);
        }
    }

    private function getConfigInstance(): Config
    {
        return Container::getInstance()->get('config');
    }
}
