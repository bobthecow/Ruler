<?php

namespace Ruler\Test\Fixtures;

class Fact
{
    public $value;

    /**
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        if ($value !== null) {
            $this->value = $value;
        }
    }
}
