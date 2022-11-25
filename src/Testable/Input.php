<?php

namespace Myerscode\Acorn\Testing\Testable;

use Myerscode\Acorn\Foundation\Console\Input\ConfigInput;
use Symfony\Component\Console\Input\InputDefinition;

class Input extends ConfigInput
{
    public function __construct(protected array $parameterArray = [], InputDefinition $inputDefinition = null)
    {
        parent::__construct($parameterArray, $inputDefinition);
    }
}
