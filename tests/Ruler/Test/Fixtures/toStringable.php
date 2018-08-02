<?php

namespace Ruler\Test\Fixtures;

class toStringable
{
    private $thingy = null;

    public function __construct($foo = null)
    {
        $this->thingy = $foo;
    }

    public function __toString()
    {
        return (string) $this->thingy;
    }
}
