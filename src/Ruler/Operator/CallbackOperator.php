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
use Ruler\Operator as BaseOperator;
use Ruler\Value;
use Ruler\VariableOperand;

/**
 * @author Jordan Raub <jordan@raub.me>
 */
class CallbackOperator extends BaseOperator
{
    protected $callback;

    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new \RuntimeException('callable must be given');
        }

        $this->callback = $callback;

        $operands = func_get_args();
        array_shift($operands);
        foreach ($operands as $operand) {
            $this->addOperand($operand);
        }
    }

    public function addOperand($operand)
    {
        if (!$operand instanceof Proposition
            && !$operand instanceof VariableOperand
        ) {
            if (!$operand instanceof Value) {
                $operand = new Value($operand);
            }
        }
        $this->operands[] = $operand;
    }

    protected function getOperandCardinality()
    {
        return static::MULTIPLE;
    }

    public function runCallback(Context $context)
    {
        $operands = array($context);

        foreach ($this->getOperands() as $operand) {
            if ($operand instanceof Proposition) {
                $operands[] = $operand->evaluate($context);
            } else if ($operand instanceof VariableOperand) {
                $operands[] = $operand->prepareValue($context);
            } else if ($operand instanceof Value) {
                $operands[] = $operand;
            }
        }
        return call_user_func_array($this->callback, $operands);
    }
}
