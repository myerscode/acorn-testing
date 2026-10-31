<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Interactions;

trait InteractsWithEnvironment
{
    /**
     * Original values of any environment variables overridden in the test,
     * keyed by name. A null value means the variable did not exist and should
     * be unset on restore.
     *
     * @var array<string, string|null>
     */
    private array $originalEnvironment = [];

    /**
     * Read an environment variable (returns null when unset).
     */
    public function getEnvironment(string $name): ?string
    {
        $value = getenv($name);

        return $value === false ? null : $value;
    }

    /**
     * Restore every environment variable overridden in the test to its
     * original value, unsetting those that did not previously exist.
     */
    public function restoreEnvironment(): void
    {
        foreach ($this->originalEnvironment as $name => $value) {
            if ($value === null) {
                putenv($name);
                unset($_ENV[$name], $_SERVER[$name]);
            } else {
                putenv($name . '=' . $value);
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }

        $this->originalEnvironment = [];
    }

    /**
     * Override an environment variable for the duration of the test.
     *
     * The original value (or its absence) is remembered and rolled back by
     * restoreEnvironment(), which AcornTestCase calls in tearDown().
     */
    public function setEnvironment(string $name, string $value): void
    {
        if (!array_key_exists($name, $this->originalEnvironment)) {
            $existing = getenv($name);
            $this->originalEnvironment[$name] = $existing === false ? null : $existing;
        }

        putenv($name . '=' . $value);
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }

    /**
     * Override several environment variables at once.
     *
     * @param array<string, string> $values
     */
    public function setEnvironmentValues(array $values): void
    {
        foreach ($values as $name => $value) {
            $this->setEnvironment($name, $value);
        }
    }
}
