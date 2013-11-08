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
 *
 * TODO: implement this as an automagical multiple operator?
 *
 * @author Jordan Raub <jordan@raub.me>
 * @extends ComparisonOperator
 */
class Min extends UnaryOperator implements ArithmeticOperator
{
    /**
     * @param Value $operand
     *
     * @return mixed
     */
    protected function evaluatePrepared(Value $operand)
    {
        return $operand->min();
    }
}
