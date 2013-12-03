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

use Ruler\Proposition;

/**
 * Logical operator base class
 *
 * @author Justin Hileman <justin@shopopensky.com>
 */
abstract class LogicalOperator extends PropositionOperator implements Proposition
{
    /**
     * array of propositions
     *
     * @param array $props
     */
    public function __construct(array $props = array())
    {
        foreach ($props as $operand) {
            $this->addOperand($operand);
        }
    }

    public function getOperands()
    {
        if (0 == count($this->operands)) {
            throw new \LogicException(get_class($this) . ' takes at least 1 operand');
        }

        return parent::getOperands();
    }
}
