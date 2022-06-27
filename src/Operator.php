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
    public const UNARY = 'UNARY';
    public const BINARY = 'BINARY';
    public const MULTIPLE = 'MULTIPLE';

    protected $operands = [];

    /**
     * @param Proposition|VariableOperand ...$operands
     */
    public function __construct(...$operands)
    {
        foreach ($operands as $operand) {
            $this->addOperand($operand);
        }
    }

    public function getOperands(): array
    {
        switch ($this->getOperandCardinality()) {
            case self::UNARY:
                if (1 !== \count($this->operands)) {
                    throw new \LogicException(static::class.' takes only 1 operand');
                }
                break;
            case self::BINARY:
                if (2 !== \count($this->operands)) {
                    throw new \LogicException(static::class.' takes 2 operands');
                }
                break;
            case self::MULTIPLE:
                if (0 === \count($this->operands)) {
                    throw new \LogicException(static::class.' takes at least 1 operand');
                }
                break;
        }

        return $this->operands;
    }

    /**
     * @param Proposition|VariableOperand $operand
     */
    abstract public function addOperand($operand): void;

    abstract protected function getOperandCardinality();
}
