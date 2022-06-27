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
use Ruler\Operator\VariableOperator;
use Ruler\RuleBuilder;
use Ruler\Variable as BaseVariable;
use Ruler\VariableOperand;

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
 * @author Justin Hileman <justin@justinhileman.info>
 */
class Variable extends BaseVariable implements \ArrayAccess
{
    private $ruleBuilder;
    private array $properties = [];

    /**
     * RuleBuilder Variable constructor.
     *
     * @param RuleBuilder $ruleBuilder
     * @param string      $name        Variable name (default: null)
     * @param mixed       $value       Default Variable value (default: null)
     */
    public function __construct(RuleBuilder $ruleBuilder, string $name = null, $value = null)
    {
        $this->ruleBuilder = $ruleBuilder;
        parent::__construct($name, $value);
    }

    /**
     * Get the RuleBuilder instance set on this Variable.
     */
    public function getRuleBuilder(): RuleBuilder
    {
        return $this->ruleBuilder;
    }

    /**
     * Get a VariableProperty for accessing methods, indexes and properties of
     * the current variable.
     *
     * @param string $name  Property name
     * @param mixed  $value The default VariableProperty value
     */
    public function getProperty(string $name, $value = null): VariableProperty
    {
        if (!isset($this->properties[$name])) {
            $this->properties[$name] = new VariableProperty($this, $name, $value);
        }

        return $this->properties[$name];
    }

    /**
     * Fluent interface method for checking whether a VariableProperty has been defined.
     *
     * @param string $name Property name
     */
    public function offsetExists($name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * Fluent interface method for creating or accessing VariableProperties.
     *
     * @see getProperty
     *
     * @param string $name Property name
     */
    public function offsetGet($name): VariableProperty
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
    public function offsetSet($name, $value): void
    {
        $this->getProperty($name)->setValue($value);
    }

    /**
     * Fluent interface method for removing a VariableProperty reference.
     *
     * @param string $name Property name
     */
    public function offsetUnset($name): void
    {
        unset($this->properties[$name]);
    }

    /**
     * Fluent interface helper to create a contains comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function stringContains($variable): Operator\StringContains
    {
        return new Operator\StringContains($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a contains comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function stringDoesNotContain($variable): Operator\StringDoesNotContain
    {
        return new Operator\StringDoesNotContain($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a insensitive contains comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function stringContainsInsensitive($variable): Operator\StringContainsInsensitive
    {
        return new Operator\StringContainsInsensitive($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a GreaterThan comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function greaterThan($variable): Operator\GreaterThan
    {
        return new Operator\GreaterThan($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a GreaterThanOrEqualTo comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function greaterThanOrEqualTo($variable): Operator\GreaterThanOrEqualTo
    {
        return new Operator\GreaterThanOrEqualTo($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a LessThan comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function lessThan($variable): Operator\LessThan
    {
        return new Operator\LessThan($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a LessThanOrEqualTo comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function lessThanOrEqualTo($variable): Operator\LessThanOrEqualTo
    {
        return new Operator\LessThanOrEqualTo($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a EqualTo comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function equalTo($variable): Operator\EqualTo
    {
        return new Operator\EqualTo($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a NotEqualTo comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function notEqualTo($variable): Operator\NotEqualTo
    {
        return new Operator\NotEqualTo($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a SameAs comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function sameAs($variable): Operator\SameAs
    {
        return new Operator\SameAs($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a NotSameAs comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function notSameAs($variable): Operator\NotSameAs
    {
        return new Operator\NotSameAs($this, $this->asVariable($variable));
    }

    public function union(...$variables): self
    {
        return $this->applySetOperator('Union', $variables);
    }

    public function intersect(...$variables): self
    {
        return $this->applySetOperator('Intersect', $variables);
    }

    public function complement(...$variables): self
    {
        return $this->applySetOperator('Complement', $variables);
    }

    public function symmetricDifference(...$variables): self
    {
        return $this->applySetOperator('SymmetricDifference', $variables);
    }

    public function min(): self
    {
        return $this->wrap(new Operator\Min($this));
    }

    public function max(): self
    {
        return $this->wrap(new Operator\Max($this));
    }

    public function containsSubset($variable): Operator\ContainsSubset
    {
        return new Operator\ContainsSubset($this, $this->asVariable($variable));
    }

    public function doesNotContainSubset($variable): Operator\DoesNotContainSubset
    {
        return new Operator\DoesNotContainSubset($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a contains comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function setContains($variable): Operator\SetContains
    {
        return new Operator\SetContains($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a contains comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function setDoesNotContain($variable): Operator\SetDoesNotContain
    {
        return new Operator\SetDoesNotContain($this, $this->asVariable($variable));
    }

    public function add($variable): self
    {
        return $this->wrap(new Operator\Addition($this, $this->asVariable($variable)));
    }

    public function divide($variable): self
    {
        return $this->wrap(new Operator\Division($this, $this->asVariable($variable)));
    }

    public function modulo($variable): self
    {
        return $this->wrap(new Operator\Modulo($this, $this->asVariable($variable)));
    }

    public function multiply($variable): self
    {
        return $this->wrap(new Operator\Multiplication($this, $this->asVariable($variable)));
    }

    public function subtract($variable): self
    {
        return $this->wrap(new Operator\Subtraction($this, $this->asVariable($variable)));
    }

    public function negate(): self
    {
        return $this->wrap(new Operator\Negation($this));
    }

    public function ceil(): self
    {
        return $this->wrap(new Operator\Ceil($this));
    }

    public function floor(): self
    {
        return $this->wrap(new Operator\Floor($this));
    }

    public function exponentiate($variable): self
    {
        return $this->wrap(new Operator\Exponentiate($this, $this->asVariable($variable)));
    }

    /**
     * Private helper to retrieve a Variable instance for the given $variable.
     *
     * @param mixed $variable BaseVariable instance or value
     */
    private function asVariable($variable): BaseVariable
    {
        return ($variable instanceof BaseVariable) ? $variable : new BaseVariable(null, $variable);
    }

    /**
     * Private helper to apply a set operator.
     */
    private function applySetOperator(string $name, array $args): self
    {
        $reflection = new \ReflectionClass('\\Ruler\\Operator\\'.$name);
        \array_unshift($args, $this);

        return $this->wrap($reflection->newInstanceArgs($args));
    }

    /**
     * Private helper to wrap a VariableOperator in a Variable instance.
     */
    private function wrap(VariableOperator $op): self
    {
        return new self($this->ruleBuilder, null, $op);
    }

    /**
     * Fluent interface helper to create a endsWith comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function endsWith($variable): Operator\EndsWith
    {
        return new Operator\EndsWith($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a endsWith insensitive comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function endsWithInsensitive($variable): Operator\EndsWithInsensitive
    {
        return new Operator\EndsWithInsensitive($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a startsWith comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function startsWith($variable): Operator\StartsWith
    {
        return new Operator\StartsWith($this, $this->asVariable($variable));
    }

    /**
     * Fluent interface helper to create a startsWith insensitive comparison operator.
     *
     * @param mixed $variable Right side of comparison operator
     */
    public function startsWithInsensitive($variable): Operator\StartsWithInsensitive
    {
        return new Operator\StartsWithInsensitive($this, $this->asVariable($variable));
    }

    /**
     * Magic method to apply operators registered with RuleBuilder.
     *
     * @see RuleBuilder::registerOperatorNamespace
     *
     * @throws \LogicException if operator is not registered
     *
     * @return Operator|self
     */
    public function __call(string $name, array $args)
    {
        $reflection = new \ReflectionClass($this->ruleBuilder->findOperator($name));
        $args = \array_map([$this, 'asVariable'], $args);
        \array_unshift($args, $this);

        $op = $reflection->newInstanceArgs($args);

        if ($op instanceof VariableOperand) {
            return $this->wrap($op);
        } else {
            return $op;
        }
    }
}
