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
 * A Contains comparison operator.
 *
 * @deprecated Please use SetContains or StringContains operators instead.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
class Contains extends VariableOperator implements Proposition
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

        $left = $left->prepareValue($context);

        if (is_array($left->getValue())) {
            trigger_error('Contains operator is deprecated, please use SetContains', E_USER_DEPRECATED);

            return $left->getSet()->setContains($right->prepareValue($context));
        } else {
            trigger_error('Contains operator is deprecated, please use StringContains', E_USER_DEPRECATED);

            return $left->stringContains($right->prepareValue($context));
        }
    }

    protected function getOperandCardinality()
    {
        return static::BINARY;
    }
}
