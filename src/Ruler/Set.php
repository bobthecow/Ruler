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
 * A Ruler Set.
 *
 * A Set is essentially an array, a special case of Value which can be compared
 * by SetOperators.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
class Set extends Value
{
    /**
     * Set constructor.
     *
     * A Set object is immutable, and is used by Variables for comparing their
     * Default values or facts from the current Context.
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
        foreach ($this->value as &$value) {
            if (is_array($value)) {
                $value = new Set($value);
            }
        }

        $this->value = array_unique($this->value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $returnValue = '';
        foreach ($this->value as $value) {
            $returnValue .= (string) $value;
        }

        return $returnValue;
    }

    /**
     * Set Contains comparison.
     *
     * @param Value $value Value object to compare against
     *
     * @return boolean
     */
    public function setContains(Value $value)
    {
        if (is_array($value->getValue())) {
            foreach ($this->value as $val) {
                if ($val instanceof Set
                    && $val == $value->getSet()) {
                    return true;
                }
            }

            return false;
        }

        return in_array($value->getValue(), $this->value);
    }

    /**
     * Set union operator.
     *
     * Returns a Set which is the union of this Set with all passed Sets.
     *
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
     * Set intersection operator.
     *
     * Returns a Set which is the intersection of this Set with all passed sets.
     *
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
     * Set complement operator.
     *
     * Returns a Set which is the complement of this Set with all passed Sets.
     *
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
     * Set symmetric difference operator.
     *
     * Returns a Set which is the symmetric difference of this Set with the
     * passed Set.
     *
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
     * Numeric minimum value in this Set.
     *
     * @throws \RuntimeException if this Set contains non-numeric members.
     *
     * @return mixed
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
     * Numeric maximum value in this Set.
     *
     * @throws \RuntimeException if this Set contains non-numeric members.
     *
     * @return mixed
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

    /**
     * Contains Subset comparison.
     *
     * @param Set $set
     *
     * @return bool
     */
    public function containsSubset(Set $set)
    {
        if (count($set->getValue()) > count($this->getValue())) {
            return false;
        }

        return array_intersect($set->getValue(), $this->getValue()) == $set->getValue();
    }

    /**
     * Helper function to validate that a set contains only numeric members.
     *
     * @return bool
     */
    protected function isValidNumericSet()
    {
        return count($this->value) == array_sum(array_map('is_numeric', $this->value));
    }
}
