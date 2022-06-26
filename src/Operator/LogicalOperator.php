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
 * @author Justin Hileman <justin@justinhileman.info>
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
}
