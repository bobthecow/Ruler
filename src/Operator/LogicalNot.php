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
 * A logical NOT operator.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
class LogicalNot extends LogicalOperator
{
    /**
     * @param Context $context Context with which to evaluate this Proposition
     */
    public function evaluate(Context $context): bool
    {
        /** @var Proposition $operand */
        [$operand] = $this->getOperands();

        return !$operand->evaluate($context);
    }

    protected function getOperandCardinality()
    {
        return static::UNARY;
    }
}
