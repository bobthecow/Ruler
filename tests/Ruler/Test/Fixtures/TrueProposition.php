<?php

namespace Ruler\Test\Fixtures;

use Ruler\Proposition;
use Ruler\Context;
use Ruler\Value;

class TrueProposition implements Proposition
{
    public function evaluate(Context $context)
    {
        return true;
    }

    /**
     * @param Context $context
     *
     * @return Value
     */
    public function prepareValue(Context $context)
    {
        return new Value(true);
    }


}
