<?php

namespace Myerscode\Acorn\Testing\Support;

use ReflectionClass;

trait UnitTestingUtilities
{

    public function setObjectProperties(object $object, $properties): void
    {
        $reflection = new ReflectionClass($object);
        foreach ($properties as $name => $value) {
            $reflection_property = $reflection->getProperty($name);
            $reflection_property->setAccessible(true);
            $reflection_property->setValue($object, $value);
        }
    }
}
