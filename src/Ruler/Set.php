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
 * A Ruler Value.
 *
 * A Value represents a comparable terminal value. Variables and Comparison Operators
 * are resolved to Values by applying the current Context and the default Variable value.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 */
class Set extends Value
{
    /**
     * Value constructor.
     *
     * A Value object is immutable, and is used by Variables for comparing their default
     * values or facts from the current Context.
     *
     * @param mixed $set Immutable value represented by this Value object
     */
    public function __construct($set)
    {
        parent::__construct($set);
        if (!is_array($this->value)) {
            if (is_null($this->value)) {
                $this->value = array();
            } else {
                $this->value = array($this->value);
            }
        }
    }

    /**
     * Contains comparison.
     *
     * @param Value $value Value object to compare against
     *
     * @return boolean
     */
    public function setContains(Value $value)
    {
        return in_array($value->getValue(), $this->value);
    }

    /**
     * @param Value $set,...
     *
     * @return Set
     */
    public function union(Value $set)
    {
        $union = $this->value;

        /** @var Value $arg */
        foreach (func_get_args() as $arg) {
            /** @var array $convertedArg */
            $convertedArg = $arg->getSet()->getValue();
            $union = array_merge($union, array_diff($convertedArg, $union));
        }

        return new self($union);
    }

    /**
     * @param Value $set,...
     *
     * @return Set
     */
    public function intersect(Value $set)
    {
        $intersect = $this->value;

        /** @var Value $arg */
        foreach (func_get_args() as $arg) {
            /** @var array $convertedArg */
            $convertedArg = $arg->getSet()->getValue();
            //array_values is needed to make sure the indexes are ordered from 0
            $intersect = array_values(array_intersect($intersect, $convertedArg));
        }
        return new self($intersect);
    }

    /**
     * @param Value $set,...
     *
     * @return Set
     */
    public function complement(Value $set)
    {
        $complement = $this->value;

        /** @var Value $arg */
        foreach (func_get_args() as $arg) {
            /** @var array $convertedArg */
            $convertedArg = $arg->getSet()->getValue();
            //array_values is needed to make sure the indexes are ordered from 0
            $complement = array_values(array_diff($complement, $convertedArg));
        }
        return new self($complement);
    }

    /**
     * @param Value $set
     *
     * @return Set
     */
    public function symmetricDifference(Value $set)
    {
        $returnValue = new Set(array());
        return $returnValue->union(
            $this->complement($set),
            $set->getSet()->complement($this)
        );
    }

    /**
     * @return Value
     */
    public function min()
    {
        if (!$this->isValidNumericSet()) {
            throw new \RuntimeException('min: all values must be numeric');
        }
        if (empty($this->value)) {
            return null;
        }
        return min($this->value);
    }

    /**
     * @return Value
     */
    public function max()
    {
        if (!$this->isValidNumericSet()) {
            throw new \RuntimeException('max: all values must be numeric');
        }
        if (empty($this->value)) {
            return null;
        }
        return max($this->value);
    }

    protected function isValidNumericSet()
    {
        return count($this->value) == array_sum(array_map('is_numeric', $this->value));
    }
}
