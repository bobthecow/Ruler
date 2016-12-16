<?php

namespace Ruler\Test\Fixtures;

use Ruler\Proposition;
use Ruler\Context;

class TrueProposition implements Proposition
{
    public function evaluate(Context $context)
    {
        return true;
    }
}
