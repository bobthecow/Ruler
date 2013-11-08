<?php

/*
 * This file is part of the Ruler package, an OpenSky project.
 *
 * (c) 2013 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ruler\RuleBuilder;

use Ruler\Operator;
use Ruler\RuleBuilder\VariableProperty;
use Ruler\Variable as BaseVariable;

/**
 * A propositional Variable.
 *
 * Variables are placeholders in Propositions and Comparison Operators. During
 * evaluation, they are replaced with terminal Values, either from the Variable
 * default or from the current Context.
 *
 * The RuleBuilder Variable extends the base Variable class with a fluent
 * interface for creating VariableProperties, Operators and Rules without all
 * kinds of awkward object instantiation.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 */
class Variable extends BaseVariable implements \ArrayAccess
{
    private $properties = array();

    /**
     * Get a VariableProperty for accessing methods, indexes and properties of
     * the current variable.
     *
     * @param  string $name  Property name
     * @param  mixed  $value The default VariableProperty value
     *
     * @return VariableProperty
     */
    public function getProperty($name, $value = null)
    {
        if (!isset($this->properties[$name])) {
            $this->properties[$name] = new VariableProperty($this, $name, $value);
        }

        return $this->properties[$name];
    }

    /**
     * Fluent interface method for checking whether a VariableProperty has been defined.
     *
     * @param  string $name Property name
     *
     * @return bool
     */
    public function offsetExists($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * Fluent interface method for creating or accessing VariableProperties.
     *
     * @see getProperty
     *
     * @param  string $name Property name
     *
     * @return VariableProperty
     */
    public function offsetGet($name)
    {
        return $this->getProperty($name);
    }

    /**
     * Fluent interface method for setting default a VariableProperty value.
     *
     * @see setValue
     *
     * @param string $name  Property name
     * @param mixed  $value The default Variable value
     */
    public function offsetSet($name, $value)
    {
        $this->getProperty($name)->setValue($value);
    }

    /**
     * Fluent interface method for removing a VariableProperty reference.
     *
     * @param string $name Property name
     */
    public function offsetUnset($name)
    {
        unset($this->properties[$name]);
    }

    /**
     * Fluent interface helper to create a contains comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     *
     * @return Operator\Contains
     */
    public function contains($variable)
    {
        return new Operator\Contains($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a contains comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     *
     * @return Operator\DoesNotContain
     */
    public function doesNotContain($variable)
    {
        return new Operator\DoesNotContain($this, $this->asVariable($variable));
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

    /**
     * Fluent interface helper to create a SameAs comparison operator.
     *
     * @param mixed $variable  Right side of comparison operator
     *
     * @return Operator\SameAs
     */
    public function sameAs($variable)
    {
        return new Operator\SameAs($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a NotSameAs comparison operator.
     *
     * @param mixed $variable  Right side of comparison operator
     *
     * @return Operator\SameAs
     */
    public function notSameAs($variable)
    {
        return new Operator\NotSameAs($this, $this->asVariable($variable));
    }

    /**
     * @param $variable
     *
     * @return Operator\Addition
     */
    public function add($variable)
    {
        return new Operator\Addition($this, $this->asVariable($variable));
    }

    /**
     * @param $variable
     *
     * @return Operator\Division
     */
    public function divide($variable)
    {
        return new Operator\Division($this, $this->asVariable($variable));
    }

    /**
     * @param $variable
     *
     * @return Operator\Modulo
     */
    public function modulo($variable)
    {
        return new Operator\Modulo($this, $this->asVariable($variable));
    }

    /**
     * @param $variable
     *
     * @return Operator\Multiplication
     */
    public function multiply($variable)
    {
        return new Operator\Multiplication($this, $this->asVariable($variable));
    }

    /**
     * @param $variable
     *
     * @return Operator\Subtraction
     */
    public function subtract($variable)
    {
        return new Operator\Subtraction($this, $this->asVariable($variable));
    }

    /**
     * @return Operator\Negation
     */
    public function negate()
    {
        return new Operator\Negation($this);
    }

    /**
     * @return Operator\Ceil
     */
    public function ceil()
    {
        return new Operator\Ceil($this);
    }

    /**
     * @return Operator\Floor
     */
    public function floor()
    {
        return new Operator\Floor($this);
    }

    /**
     * @param $variable
     *
     * @return Operator\Exponentiate
     */
    public function exponentiate($variable)
    {
        return new Operator\Exponentiate($this, $this->asVariable($variable));
    }

    /**
     * @return Operator\Min
     */
    public function min()
    {
        return new Operator\Min($this);
    }

    /**
     * @return Operator\Max
     */
    public function max()
    {
        return new Operator\Max($this);
    }

    /**
     * Private helper to retrieve a Variable instance for the given $variable.
     *
     * @param mixed $variable BaseVariable instance or value
     *
     * @return BaseVariable
     */
    private function asVariable($variable)
    {
        return ($variable instanceof BaseVariable) ? $variable : new BaseVariable(null, $variable);
    }
}
