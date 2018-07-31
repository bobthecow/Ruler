<?php

/*
 * This file is part of the Ruler package, an OpenSky project.
 *
 * (c) 2011 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ruler;

/**
 * @author Jordan Raub <jordan@raub.me>
 */
abstract class Operator
{
    const UNARY = 'UNARY';
    const BINARY = 'BINARY';
    const MULTIPLE = 'MULTIPLE';

    protected $operands = array();

    /**
     * @param array $operands
     */
    public function __construct()
    {
        foreach (func_get_args() as $operand) {
            $this->addOperand($operand);
        }
    }

    public function getOperands()
    {
        switch ($this->getOperandCardinality()) {
            case self::UNARY:
                if (1 != count($this->operands)) {
                    throw new \LogicException(get_class($this) . ' takes only 1 operand');
                }
                break;
            case self::BINARY:
                if (2 != count($this->operands)) {
                    throw new \LogicException(get_class($this) . ' takes 2 operands');
                }
                break;
            case self::MULTIPLE:
                if (0 == count($this->operands)) {
                    throw new \LogicException(get_class($this) . ' takes at least 1 operand');
                }
                break;
        }

        return $this->operands;
    }

    abstract public function addOperand($operand);
    abstract protected function getOperandCardinality();
}
