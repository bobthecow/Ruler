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
use Ruler\Variable;
use Ruler\Value;
use Ruler\Proposition;

/**
 * Abstract Comparison Operator class.
 *
 * @abstract
 * @author Jordan Raub <jordan@raub.me>
 * @implements Proposition
 */
abstract class BinaryOperator implements Proposition
{
    /**
     * @var \Ruler\Proposition|\Ruler\Variable
     */
    protected $left;

    /**
     * @var \Ruler\Proposition|\Ruler\Variable
     */
    protected $right;

    /**
     * Binary Operator constructor.
     *
     * @param mixed $left  Left side of comparison
     * @param mixed $right Right side of comparison
     *
     * @throws \RuntimeException
     */
    public function __construct($left, $right)
    {
        if (!$left instanceof Proposition && !$left instanceof Variable) {
            throw new \RuntimeException("left value must be a Proposition or Variable");
        }
        if (!$right instanceof Proposition && !$right instanceof Variable) {
            throw new \RuntimeException("right value must be a Proposition or Variable");
        }
        $this->left  = $left;
        $this->right = $right;
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
        $left = $this->left;
        if ($left instanceof Proposition) {
            $left = new Value($left->evaluate($context));
        } else if ($left instanceof Variable) {
            $left = $left->prepareValue($context);
        }

        $right = $this->right;
        if ($right instanceof Proposition) {
            $right = new Value($right->evaluate($context));
        } else if ($right instanceof Variable) {
            $right = $right->prepareValue($context);
        }

        return $this->evaluatePrepared($left, $right);
    }

    abstract protected function evaluatePrepared(Value $left, Value $right);
}
