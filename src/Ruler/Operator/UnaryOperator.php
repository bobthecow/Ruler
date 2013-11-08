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
use Ruler\Variable;
use Ruler\Context;
use Ruler\Value;

/**
 * Abstract Comparison Operator class.
 *
 * @abstract
 * @author Jordan Raub <jordan@raub.me>
 * @implements Proposition
 */
abstract class UnaryOperator implements Proposition
{
    /**
     * @var \Ruler\Proposition|\Ruler\Variable
     */
    protected $operand;

    /**
     * @param mixed $operand
     *
     * @throws \RuntimeException
     */
    public function __construct($operand)
    {
        if (!$operand instanceof Proposition && !$operand instanceof Variable) {
            throw new \RuntimeException("operand must be a Proposition or Variable");
        }
        $this->operand  = $operand;
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
        $operand = $this->operand;
        if ($operand instanceof Proposition) {
            $operand = new Value($operand->evaluate($context));
        } else if ($operand instanceof Variable) {
            $operand = $operand->prepareValue($context);
        }
        return $this->evaluatePrepared($operand);
    }

    abstract protected function evaluatePrepared(Value $operand);
}
