<?php

namespace Ruler;

use Ruler\Context;
use Ruler\Value;

interface VariableOperand
{
    /**
     * @param Context $context
     *
     * @return Value
     */
    public function prepareValue(Context $context);
}
