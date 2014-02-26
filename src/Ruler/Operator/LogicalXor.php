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
 * A logical XOR operator.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
class LogicalXor extends LogicalOperator
{
    /**
     * @param Context $context Context with which to evaluate this Proposition
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        $true = 0;
        /** @var Proposition $operand */
        foreach ($this->getOperands() as $operand) {
            if (true === $operand->evaluate($context)) {
                if (++$true > 1) {
                    return false;
                }
            }
        }

        return $true === 1;
    }

    protected function getOperandCardinality()
    {
        return static::MULTIPLE;
    }
}
