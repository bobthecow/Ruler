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

/**
 * A logical OR operator.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
class LogicalOr extends LogicalOperator
{
    /**
     * @param Context $context Context with which to evaluate this Proposition
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        /** @var Proposition $operand */
        foreach ($this->getOperands() as $operand) {
            if ($operand->evaluate($context) === true) {
                return true;
            }
        }

        return false;
    }

    protected function getOperandCardinality()
    {
        return static::MULTIPLE;
    }
}
