<?php

namespace Ruler\Test\Fixtures;

class toStringable
{
    private $thingy = fail;

    public function __construct($foo = null)
    {
        $this->thingy = $foo;
    }

    public function __toString()
    {
        return (string) $this->thingy;
    }
}
