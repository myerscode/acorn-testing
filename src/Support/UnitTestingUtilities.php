<?php

declare(strict_types=1);

namespace Myerscode\Acorn\Testing\Support;

use ReflectionClass;

trait UnitTestingUtilities
{
    /**
     * @param array<string, mixed> $properties
     */
    public function setObjectProperties(object $object, array $properties): void
    {
        $reflectionClass = new ReflectionClass($object);
        foreach ($properties as $name => $value) {
            $reflection_property = $reflectionClass->getProperty($name);
            $reflection_property->setValue($object, $value);
        }
    }
}
