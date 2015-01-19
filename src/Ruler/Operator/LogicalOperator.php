<?php

/*
 * This file is part of the Ruler package, an OpenSky project.
 *
 * (c) 2011 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ruler\Operator;

use Ruler\Context;
use Ruler\Proposition;
use Ruler\Value;
use Ruler\VariableOperand;

/**
 * Logical operator base class
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
abstract class LogicalOperator extends PropositionOperator implements Proposition, VariableOperand
{
    /**
     * array of propositions
     *
     * @param array $props
     */
    public function __construct(array $props = array())
    {
        foreach ($props as $operand) {
            $this->addOperand($operand);
        }
    }

    /**
     * @param Context $context
     *
     * @return Value
     */
    public function prepareValue(Context $context)
    {
        if (($value = $this->evaluate($context)) instanceof Value) {
            return $value;
        }

        return new Value($value);
    }
}
