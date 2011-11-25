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

/**
 * A logical XOR operator.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 * @extends LogicalOperator
 */
class LogicalXor extends LogicalOperator
{
    /**
     * Evaluate whether exactly one child Proposition evaluates to true given the current Context.
     *
     * @param Context $context Context with which to evaluate this ComparisonOperator
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        if (empty($this->propositions)) {
            throw new \LogicException('Logical Xor requires at least one proposition');
        }

        $true = 0;
        foreach ($this->propositions as $prop) {
            if ($prop->evaluate($context) === true) {
                if (++$true > 1) {
                    return false;
                }
            }
        }

        return $true === 1;
    }
}