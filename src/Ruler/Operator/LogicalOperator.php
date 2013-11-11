<?php

namespace Ruler\Operator;

use Ruler\Proposition;

abstract class LogicalOperator extends PropositionOperator implements Proposition
{
    public function __construct(array $props = array())
    {
        foreach ($props as $operand) {
            $this->addOperand($operand);
        }
    }
}
