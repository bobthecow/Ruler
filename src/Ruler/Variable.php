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

use Ruler\Operator;
use Ruler\Context;

/**
 * A propositional Variable.
 *
 * Variables are placeholders in Propositions and Comparison Operators. During
 * evaluation, they are replaced with terminal Values, either from the Variable
 * default or from the current Context.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 */
class Variable
{
    private $name;
    private $value;

    /**
     * Variable class constructor.
     *
     * @param string $name  Variable name (default: null)
     * @param mixed  $value Default Variable value (default: null)
     */
    public function __construct($name = null, $value = null)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * Return the Variable name.
     *
     * @return string Variable name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the default Variable value.
     *
     * @param mixed $value The default Variable value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get the default Variable value.
     *
     * @return mixed Variable value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Prepare a Value for this Variable given the current Context.
     *
     * @param Context $context The current Context
     *
     * @return Value
     */
    public function prepareValue(Context $context)
    {
        if (isset($this->name) && isset($context[$this->name])) {
            $value = $context[$this->name];
        } else {
            $value = $this->value;
        }

        return ($value instanceof Value) ? $value : new Value($value);
    }

    /**
     * Fluent interface helper to create a GreaterThan comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     *
     * @return Operator\GreaterThan
     */
    public function greaterThan($variable)
    {
        return new Operator\GreaterThan($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a GreaterThanOrEqualTo comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     *
     * @return Operator\GreaterThanOrEqualTo
     */
    public function greaterThanOrEqualTo($variable)
    {
        return new Operator\GreaterThanOrEqualTo($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a LessThan comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     *
     * @return Operator\LessThan
     */
    public function lessThan($variable)
    {
        return new Operator\LessThan($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a LessThanOrEqualTo comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     *
     * @return Operator\LessThanOrEqualTo
     */
    public function lessThanOrEqualTo($variable)
    {
        return new Operator\LessThanOrEqualTo($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a EqualTo comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     *
     * @return Operator\EqualTo
     */
    public function equalTo($variable)
    {
        return new Operator\EqualTo($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a NotEqualTo comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     *
     * @return Operator\NotEqualTo
     */
    public function notEqualTo($variable)
    {
        return new Operator\NotEqualTo($this, $this->asVariable($variable));
    }

    public function sameAs($variable)
    {
        return new Operator\SameAs($this, $this->asVariable($variable));
    }

    public function notSameAs($variable)
    {
        return new Operator\NotSameAs($this, $this->asVariable($variable));
    }

    /**
     * Private helper to retrieve a Variable instance for the given $variable.
     *
     * @param mixed $variable Variable instance or value
     *
     * @return Variable
     */
    private function asVariable($variable)
    {
        return ($variable instanceof Variable) ? $variable : new Variable(null, $variable);
    }


}