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

    public function __toString(): string
    {
        if (\is_object($this->value)) {
            return \spl_object_hash($this->value);
        } else {
            return \serialize($this->value);
        }
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
     */
    public function getSet(): Set
    {
        return new Set($this->value);
    }

    /**
     * Equal To comparison.
     *
     * @param Value $value Value object to compare against
     */
    public function equalTo(self $value): bool
    {
        return $this->value === $value->getValue();
    }

    /**
     * Identical To comparison.
     *
     * @param Value $value Value object to compare against
     */
    public function sameAs(self $value): bool
    {
        return $this->value === $value->getValue();
    }

    /**
     * String Contains comparison.
     *
     * @param Value $value Value object to compare against
     */
    public function stringContains(self $value): bool
    {
        return \strpos($this->value, (string) $value->getValue()) !== false;
    }

    /**
     * String Contains case-insensitive comparison.
     *
     * @param Value $value Value object to compare against
     */
    public function stringContainsInsensitive(self $value): bool
    {
        return \stripos($this->value, (string) $value->getValue()) !== false;
    }

    /**
     * Greater Than comparison.
     *
     * @param Value $value Value object to compare against
     */
    public function greaterThan(self $value): bool
    {
        return $this->value > $value->getValue();
    }

    /**
     * Less Than comparison.
     *
     * @param Value $value Value object to compare against
     */
    public function lessThan(self $value): bool
    {
        return $this->value < $value->getValue();
    }

    public function add(self $value)
    {
        if (!\is_numeric($this->value) || !\is_numeric($value->getValue())) {
            throw new \RuntimeException('Arithmetic: values must be numeric');
        }

        return $this->value + $value->getValue();
    }

    public function divide(self $value)
    {
        if (!\is_numeric($this->value) || !\is_numeric($value->getValue())) {
            throw new \RuntimeException('Arithmetic: values must be numeric');
        }
        if (0 === $value->getValue()) {
            throw new \RuntimeException('Division by zero');
        }

        return $this->value / $value->getValue();
    }

    public function modulo(self $value)
    {
        if (!\is_numeric($this->value) || !\is_numeric($value->getValue())) {
            throw new \RuntimeException('Arithmetic: values must be numeric');
        }
        if (0 === $value->getValue()) {
            throw new \RuntimeException('Division by zero');
        }

        return $this->value % $value->getValue();
    }

    public function multiply(self $value)
    {
        if (!\is_numeric($this->value) || !\is_numeric($value->getValue())) {
            throw new \RuntimeException('Arithmetic: values must be numeric');
        }

        return $this->value * $value->getValue();
    }

    public function subtract(self $value)
    {
        if (!\is_numeric($this->value) || !\is_numeric($value->getValue())) {
            throw new \RuntimeException('Arithmetic: values must be numeric');
        }

        return $this->value - $value->getValue();
    }

    public function negate()
    {
        if (!\is_numeric($this->value)) {
            throw new \RuntimeException('Arithmetic: values must be numeric');
        }

        return -$this->value;
    }

    public function ceil()
    {
        if (!\is_numeric($this->value)) {
            throw new \RuntimeException('Arithmetic: values must be numeric');
        }

        return (int) \ceil($this->value);
    }

    public function floor()
    {
        if (!\is_numeric($this->value)) {
            throw new \RuntimeException('Arithmetic: values must be numeric');
        }

        return (int) \floor($this->value);
    }

    public function exponentiate(self $value)
    {
        if (!\is_numeric($this->value) || !\is_numeric($value->getValue())) {
            throw new \RuntimeException('Arithmetic: values must be numeric');
        }

        return $this->value ** $value->getValue();
    }

    /**
     * String StartsWith comparison.
     *
     * @param Value $value       Value object to compare against
     * @param bool  $insensitive Perform a case-insensitive comparison (default: false)
     */
    public function startsWith(self $value, bool $insensitive = false): bool
    {
        $value = $value->getValue();
        $valueLength = \strlen($value);

        if (!empty($this->value) && !empty($value) && \strlen($this->value) >= $valueLength) {
            return \substr_compare($this->value, $value, 0, $valueLength, $insensitive) === 0;
        }

        return false;
    }

    /**
     * String EndsWith comparison.
     *
     * @param Value $value       Value object to compare against
     * @param bool  $insensitive Perform a case-insensitive comparison (default: false)
     */
    public function endsWith(self $value, bool $insensitive = false): bool
    {
        $value = $value->getValue();
        $valueLength = \strlen($value);

        if (!empty($this->value) && !empty($value) && \strlen($this->value) >= $valueLength) {
            return \substr_compare($this->value, $value, -$valueLength, $valueLength, $insensitive) === 0;
        }

        return false;
    }
}
