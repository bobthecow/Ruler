<?php

namespace Ruler\Test\Fixtures;

class Invokable
{
    public function __invoke($value = null)
    {
        return new Fact($value);
    }
}
