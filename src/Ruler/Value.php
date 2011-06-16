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
     * @param mixed $value
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
     * @param  Value $value
     * @return boolean
     */
    public function equalTo(Value $value)
    {
        return $this->value == $value->getValue();
    }

    /**
     * Greater Than comparison.
     *
     * @param  Value $value
     * @return boolean
     */
    public function greaterThan(Value $value)
    {
        return $this->value > $value->getValue();
    }

    /**
     * Less Than comparison.
     *
     * @param  Value $value
     * @return boolean
     */
    public function lessThan(Value $value)
    {
        return $this->value < $value->getValue();
    }
}