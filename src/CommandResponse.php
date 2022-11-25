<?php

namespace Myerscode\Acorn\Testing;

use Myerscode\Acorn\Foundation\Console\Input\ConfigInput;
use Myerscode\Acorn\Testing\Support\Assertions;
use Myerscode\Acorn\Testing\Testable\Output;

class CommandResponse
{
    public function __construct(readonly ConfigInput $input, readonly Output $output)
    {
        //
    }

    public function assertOutputEquals(string $expected): self
    {
        Assertions::assertEquals($this->output->output(), $expected);

        return $this;
    }
}
