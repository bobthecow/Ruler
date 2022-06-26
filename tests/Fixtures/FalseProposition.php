<?php

namespace Ruler\Test\Fixtures;

use Ruler\Proposition;
use Ruler\Context;

class FalseProposition implements Proposition
{
    public function evaluate(Context $context)
    {
        return false;
    }
}
