<?php

namespace Ruler\Test\Fixtures;

class Invokable
{
    /**
     * @param mixed $value
     */
    public function __invoke($value = null)
    {
        return new Fact($value);
    }
}
