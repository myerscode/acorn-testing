<?php

namespace Myerscode\Acorn\Testing\Interactions;

trait InteractsWithPhpFile
{
    public function getClassTraits(string|object $class): array
    {
        if (is_object($class)) {
            $class = $class::class;
        }

        $results = [];

        foreach (array_reverse(class_parents($class)) + [$class => $class] as $class) {
            $results += $this->getTraits($class);
        }

        return array_unique($results);
    }

    protected function getTraits($trait): array
    {
        $traits = class_uses($trait);

        foreach ($traits as $trait) {
            $traits += $this->getTraits($trait);
        }

        return $traits;
    }
}
