<?php

namespace Ruler\Test\Fixtures;

use Ruler\Context;
use Ruler\Proposition;

class TrueProposition implements Proposition
{
    public function evaluate(Context $context)
    {
        return true;
    }
}
