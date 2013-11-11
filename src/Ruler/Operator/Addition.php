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
use Ruler\VariableOperand;

/**
 * An Addition Arithmetic Operator
 *
 * @author Jordan Raub <jordan@raub.me>
 */
class Addition extends VariableOperator implements VariableOperand
{
    /**
     * @param Context $context
     *
     * @return int|\Ruler\Value
     */
    public function prepareValue(Context $context)
    {
        /** @var VariableOperand $left */
        /** @var VariableOperand $right */
        list($left, $right) = $this->getOperands();
        return $left->prepareValue($context)->add($right->prepareValue($context));
    }

    protected function getOperandCardinality()
    {
        return static::BINARY;
    }
}
