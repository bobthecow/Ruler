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

use Ruler\Operator as BaseOperator;
use Ruler\Proposition;

/**
 * @author Jordan Raub <jordan@raub.me>
 */
abstract class PropositionOperator extends BaseOperator
{
    /**
     * @param Proposition $operand
     */
    public function addOperand($operand): void
    {
        $this->addProposition($operand);
    }

    public function addProposition(Proposition $operand): void
    {
        if (static::UNARY === $this->getOperandCardinality()
            && 0 < \count($this->operands)
        ) {
            throw new \LogicException(static::class.' can only have 1 operand');
        }
        $this->operands[] = $operand;
    }
}
