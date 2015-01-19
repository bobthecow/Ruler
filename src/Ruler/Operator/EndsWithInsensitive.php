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
use Ruler\VariableOperand;

/**
 * A EndsWith insensitive comparison operator.
 *
 * @author Cornel Les <thebogu@gmail.com>
 */
class EndsWithInsensitive extends VariableOperator implements Proposition
{
    /**
     * @param Context $context Context with which to evaluate this Proposition
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        /** @var VariableOperand $left */
        /** @var VariableOperand $right */
        list($left, $right) = $this->getOperands();

        return $left->prepareValue($context)->endsWith($right->prepareValue($context), true);
    }

    /**
     * @param Context $context
     *
     * @return Value
     */
    public function prepareValue(Context $context)
    {
        return new Value($this->evaluate($context));
    }

    protected function getOperandCardinality()
    {
        return static::BINARY;
    }
}
