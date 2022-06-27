<?php

namespace Ruler\Test\Fixtures;

class toStringable
{
    private $thingy = null;

    /**
     * @param mixed $foo
     */
    public function __construct($foo = null)
    {
        $this->thingy = $foo;
    }

    public function __toString(): string
    {
        return (string) $this->thingy;
    }
}
