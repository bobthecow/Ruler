<?php

namespace Ruler\Test\Fixtures;

use Ruler\Test\Fixtures\Fact;

class Invokable
{
    public function __invoke($value = null)
    {
        return new Fact($value);
    }
}
