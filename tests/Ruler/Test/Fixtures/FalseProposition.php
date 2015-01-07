<?php

namespace Ruler\Test\Fixtures;

use Ruler\Proposition;
use Ruler\Context;
use Ruler\Value;

class FalseProposition implements Proposition
{
    public function evaluate(Context $context)
    {
        return false;
    }

    /**
     * @param Context $context
     *
     * @return Value
     */
    public function prepareValue(Context $context)
    {
        return new Value(false);
    }


}
