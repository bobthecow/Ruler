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
     * Contains comparison.
     *
     * @param Value $value Value object to compare against
     *
     * @return boolean
     */
    public function contains(Value $value)
    {
        if (is_array($this->value)) {
            return in_array($value->getValue(), $this->value);
        } elseif (is_string($this->value)) {
            return strpos($this->value, $value->getValue()) !== false;
        }

        return false;
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
     * Check if the string starts with a string
     *
     * @param Value $value
     *
     * @return bool
     */
    public function startsWith(Value $value)
    {
        $value = $value->getValue();
        if (!empty($this->value) && !empty($value) && strlen($this->value) >= strlen($value)) {
            return substr_compare($this->value, $value, 0, strlen($value)) === 0;
        }

        return false;
    }

    /**
     * Check if the string ends with a string
     *
     * @param Value $value
     *
     * @return bool
     */
    public function endsWith(Value $value)
    {
        $value = $value->getValue();
        if (!empty($this->value) && !empty($value) && strlen($this->value) >= strlen($value)) {
            return substr_compare($this->value, $value, -strlen($value)) === 0;
        }

        return false;
    }
}
