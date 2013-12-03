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
 * RuleBuilder.
 *
 * The RuleBuilder provides a DSL and fluent interface for constructing
 * Rules.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 * @implements ArrayAccess
 */
class RuleBuilder implements \ArrayAccess
{
    private $variables;

    /**
     * RuleBuilder constructor.
     */
    public function __construct()
    {
        $this->variables = array();
    }

    /**
     * Create a Rule with the given propositional condition.
     *
     * @param Proposition $condition Propositional condition for this Rule
     * @param callback    $action    Action (callable) to take upon successful Rule execution (default: null)
     *
     * @return Rule
     */
    public function create(Proposition $condition, $action = null)
    {
        return new Rule($condition, $action);
    }

    /**
     * Create a logical AND operator proposition.
     *
     * @param Proposition $prop     Initial Proposition
     * @param Proposition $prop2,... Optional unlimited number of additional Propositions
     *
     * @return Operator\LogicalAnd
     */
    public function logicalAnd(Proposition $prop, Proposition $prop2 = null)
    {
        return new Operator\LogicalAnd(func_get_args());
    }

    /**
     * Create a logical OR operator proposition.
     *
     * @param Proposition $prop     Initial Proposition
     * @param Proposition $prop2,... Optional unlimited number of additional Propositions
     *
     * @return Operator\LogicalOr
     */
    public function logicalOr(Proposition $prop, Proposition $prop2 = null)
    {
        return new Operator\LogicalOr(func_get_args());
    }

    /**
     * Create a logical NOT operator proposition.
     *
     * @param Proposition $prop Exactly one Proposition
     *
     * @return Operator\LogicalNot
     */
    public function logicalNot(Proposition $prop)
    {
        return new Operator\LogicalNot(array($prop));
    }

    /**
     * Create a logical XOR operator proposition.
     *
     * @param Proposition $prop     Initial Proposition
     * @param Proposition $prop2,... Optional unlimited number of additional Propositions
     *
     * @return Operator\LogicalXor
     */
    public function logicalXor(Proposition $prop, Proposition $prop2 = null)
    {
        return new Operator\LogicalXor(func_get_args());
    }

    /**
     * @param $callback
     *
     * @return Operator\CallbackProposition
     */
    public function callbackProposition($callback)
    {
        $reflection = new \ReflectionClass('\\Ruler\\Operator\\CallbackProposition');

        return $reflection->newInstanceArgs(func_get_args());
    }

    /**
     * @param $callback
     *
     * @return RuleBuilder\Variable
     */
    public function callbackVariable($callback)
    {
        $reflection = new \ReflectionClass('\\Ruler\\Operator\\CallbackVariableOperand');

        return new RuleBuilder\Variable(null, $reflection->newInstanceArgs(func_get_args()));
    }

    /**
     * Check whether a Variable is already set.
     *
     * @param string $name The Variable name
     *
     * @return boolean
     */
    public function offsetExists($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * Retrieve a Variable by name.
     *
     * @param string $name The Variable name
     *
     * @return RuleBuilder\Variable
     */
    public function offsetGet($name)
    {
        if (!isset($this->variables[$name])) {
            $this->variables[$name] = new RuleBuilder\Variable($name);
        }

        return $this->variables[$name];
    }

    /**
     * Set the default value of a Variable.
     *
     * @param string $name  The Variable name
     * @param mixed  $value The Variable default value
     */
    public function offsetSet($name, $value)
    {
        $this->offsetGet($name)->setValue($value);
    }

    /**
     * Remove a defined Variable from the RuleBuilder.
     *
     * @param string $name The Variable name
     */
    public function offsetUnset($name)
    {
        unset($this->variables[$name]);
    }
}
