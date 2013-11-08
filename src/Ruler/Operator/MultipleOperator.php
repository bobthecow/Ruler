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
use Ruler\Value;
use Ruler\Variable;

/**
 * Abstract Operator class with multiple Operands.
 *
 * @abstract
 * @author Justin Hileman <justin@shopopensky.com>
 * @implements Proposition
 */
abstract class MultipleOperator implements Proposition
{
    /**
     * @var array
     */
    protected $operands = array();

    /**
     * @param array $operands Initial Propositions to add to the Operator (default: null)
     */
    public function __construct(array $operands = array())
    {
        foreach ($operands as $operand) {
            $this->addOperand($operand);
        }
    }

    /**
     * @param $operand
     *
     * @return $this
     * @throws \RuntimeException
     */
    public function addOperand($operand)
    {
        if ($operand instanceof Proposition) {
            $this->addProposition($operand);
        } else if ($operand instanceof Variable) {
            $this->addVariable($operand);
        } else {
            throw new \RuntimeException("operand must be a Proposition or Variable");
        }
        return $this;
    }

    /**
     * @param Proposition $prop
     */
    public function addProposition(Proposition $prop)
    {
        $this->operands[] = $prop;
    }

    /**
     * @param Variable $var
     */
    public function addVariable(Variable $var)
    {
        $this->operands[] = $var;
    }

    /**
     * Evaluate the Proposition with the given Context.
     *
     * @param Context $context Context with which to evaluate this Proposition
     *
     * @return boolean
     */
    final public function evaluate(Context $context)
    {
        $operands = array();
        foreach ($this->operands as $operand) {
            if ($operand instanceof Proposition) {
                /** @var Proposition $operand */
                $operands[] = new Value($operand->evaluate($context));
            } else if ($operand instanceof Variable) {
                /** @var Variable $this->operand */
                $operands[] = $operand->prepareValue($context);
            }
        }
        return $this->evaluatePrepared($operands);
    }

    abstract protected function evaluatePrepared(array $operands);
}
