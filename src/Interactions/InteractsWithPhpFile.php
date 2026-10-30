<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Interactions;

trait InteractsWithPhpFile
{
    /**
     * @return array<string, string>
     */
    public function getClassTraits(string|object $class): array
    {
        if (is_object($class)) {
            $class = $class::class;
        }

        $results = [];

        foreach (array_reverse((array) class_parents($class)) + [$class => $class] as $className) {
            $results += $this->getTraits((string) $className);
        }

        return array_unique($results);
    }

    /**
     * @return array<string, string>
     */
    protected function getTraits(string $traitName): array
    {
        $traits = class_uses($traitName);

        if ($traits === false) {
            return [];
        }

        foreach (array_keys($traits) as $usedTrait) {
            $traits += $this->getTraits($usedTrait);
        }

        return $traits;
    }
}
