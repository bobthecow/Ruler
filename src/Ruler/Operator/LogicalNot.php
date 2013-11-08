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

use Ruler\Value;

/**
 * A logical NOT operator.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 * @extends LogicalOperator
 */
class LogicalNot extends UnaryOperator implements LogicalOperator
{
    /**
     * @param Value $operand
     *
     * @return bool
     */
    protected function evaluatePrepared(Value $operand)
    {
        return !$operand->getValue();
    }
}
