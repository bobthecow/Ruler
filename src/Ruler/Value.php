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
 * @author Justin Hileman <justin@justinhileman.info>
 */
class Value
{
    protected $value;

    /**
     * Value constructor.
     *
     * A Value object is immutable, and is used by Variables for comparing their default
     * values or facts from the current Context.
     *
     * @param mixed $value Immutable value represented by this Value object
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Return the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get a Set containing this Value.
     *
     * @return Set
     */
    public function getSet()
    {
        return new Set($this->value);
    }

    /**
     * Equal To comparison.
     *
     * @param Value $value Value object to compare against
     *
     * @return boolean
     */
    public function equalTo(Value $value)
    {
        return $this->value == $value->getValue();
    }

    /**
     * Identical To comparison.
     *
     * @param Value $value Value object to compare against
     *
     * @return boolean
     */
    public function sameAs(Value $value)
    {
        return $this->value === $value->getValue();
    }

    /**
     * String Contains comparison.
     *
     * @param Value $value Value object to compare against
     *
     * @return boolean
     */
    public function stringContains(Value $value)
    {
        return strpos($this->value, $value->getValue()) !== false;
    }

    /**
     * String Contains case-insensitive comparison.
     *
     * @param Value $value Value object to compare against
     *
     * @return boolean
     */
    public function stringContainsInsensitive(Value $value)
    {
        return stripos($this->value, $value->getValue()) !== false;
    }

    /**
     * Greater Than comparison.
     *
     * @param Value $value Value object to compare against
     *
     * @return boolean
     */
    public function greaterThan(Value $value)
    {
        return $this->value > $value->getValue();
    }

    /**
     * Less Than comparison.
     *
     * @param Value $value Value object to compare against
     *
     * @return boolean
     */
    public function lessThan(Value $value)
    {
        return $this->value < $value->getValue();
    }

    public function add(Value $value)
    {
        if (!is_numeric($this->value) || !is_numeric($value->getValue())) {
            throw new \RuntimeException("Arithmetic: values must be numeric");
        }

        return $this->value + $value->getValue();
    }

    public function divide(Value $value)
    {
        if (!is_numeric($this->value) || !is_numeric($value->getValue())) {
            throw new \RuntimeException("Arithmetic: values must be numeric");
        }
        if (0 == $value->getValue()) {
            throw new \RuntimeException("Division by zero");
        }

        return $this->value / $value->getValue();
    }

    public function modulo(Value $value)
    {
        if (!is_numeric($this->value) || !is_numeric($value->getValue())) {
            throw new \RuntimeException("Arithmetic: values must be numeric");
        }
        if (0 == $value->getValue()) {
            throw new \RuntimeException("Division by zero");
        }

        return $this->value % $value->getValue();
    }

    public function multiply(Value $value)
    {
        if (!is_numeric($this->value) || !is_numeric($value->getValue())) {
            throw new \RuntimeException("Arithmetic: values must be numeric");
        }

        return $this->value * $value->getValue();
    }

    public function subtract(Value $value)
    {
        if (!is_numeric($this->value) || !is_numeric($value->getValue())) {
            throw new \RuntimeException("Arithmetic: values must be numeric");
        }

        return $this->value - $value->getValue();
    }

    public function negate()
    {
        if (!is_numeric($this->value)) {
            throw new \RuntimeException("Arithmetic: values must be numeric");
        }

        return -$this->value;
    }

    public function ceil()
    {
        if (!is_numeric($this->value)) {
            throw new \RuntimeException("Arithmetic: values must be numeric");
        }

        return (int) ceil($this->value);
    }

    public function floor()
    {
        if (!is_numeric($this->value)) {
            throw new \RuntimeException("Arithmetic: values must be numeric");
        }

        return (int) floor($this->value);
    }

    public function exponentiate(Value $value)
    {
        if (!is_numeric($this->value) || !is_numeric($value->getValue())) {
            throw new \RuntimeException("Arithmetic: values must be numeric");
        }

        return pow($this->value, $value->getValue());
    }

    /**
     * String StartsWith comparison.
     *
     * @param Value $value       Value object to compare against
     * @param bool  $insensitive Perform a case-insensitive comparison (default: false)
     *
     * @return boolean
     */
    public function startsWith(Value $value, $insensitive = false)
    {
        $value = $value->getValue();
        $valueLength = strlen($value);

        if (!empty($this->value) && !empty($value) && strlen($this->value) >= $valueLength) {
            return substr_compare($this->value, $value, 0, $valueLength, $insensitive) === 0;
        }

        return false;
    }

    /**
     * String EndsWith comparison.
     *
     * @param Value $value       Value object to compare against
     * @param bool  $insensitive Perform a case-insensitive comparison (default: false)
     *
     * @return boolean
     */
    public function endsWith(Value $value, $insensitive = false)
    {
        $value = $value->getValue();
        $valueLength = strlen($value);

        if (!empty($this->value) && !empty($value) && strlen($this->value) >= $valueLength) {
            return substr_compare($this->value, $value, -$valueLength, $valueLength, $insensitive) === 0;
        }

        return false;
    }
}
