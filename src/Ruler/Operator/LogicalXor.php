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
 * A logical XOR operator.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 * @extends LogicalOperator
 */
class LogicalXor extends MultipleOperator implements LogicalOperator
{
    /**
     * @param array $operands
     *
     * @return bool
     * @throws \LogicException
     */
    protected function evaluatePrepared(array $operands)
    {
        if (empty($operands)) {
            throw new \LogicException('Logical Xor requires at least one proposition');
        }

        $true = 0;
        /** @var Value $operand */
        foreach ($operands as $operand) {
            if ($operand->getValue() === true) {
                if (++$true > 1) {
                    return false;
                }
            }
        }

        return $true === 1;
    }
}
