<?php

namespace Ruler\Test\Fixtures;

class Fact
{
    public function __construct($value = null)
    {
        if ($value !== null) {
            $this->value = $value;
        }
    }
}
