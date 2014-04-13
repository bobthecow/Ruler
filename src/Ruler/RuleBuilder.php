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
 * @author Justin Hileman <justin@justinhileman.info>
 */
class RuleBuilder implements \ArrayAccess
{
    private $variables          = array();
    private $operatorNamespaces = array();

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
     * Register an operator namespace.
     *
     * Note that, depending on your filesystem, operator namespaces are most likely case sensitive.
     *
     * @throws \InvalidArgumentException
     *
     * @param string $namespace Operator namespace
     *
     * @return RuleBuilder
     */
    public function registerOperatorNamespace($namespace)
    {
        if (!is_string($namespace)) {
            throw new \InvalidArgumentException('Namespace argument must be a string');
        }

        $this->operatorNamespaces[$namespace] = true;

        return $this;
    }

    /**
     * Create a logical AND operator proposition.
     *
     * @param Proposition $prop      Initial Proposition
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
     * @param Proposition $prop      Initial Proposition
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
     * @param Proposition $prop      Initial Proposition
     * @param Proposition $prop2,... Optional unlimited number of additional Propositions
     *
     * @return Operator\LogicalXor
     */
    public function logicalXor(Proposition $prop, Proposition $prop2 = null)
    {
        return new Operator\LogicalXor(func_get_args());
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
     * @return Variable
     */
    public function offsetGet($name)
    {
        if (!isset($this->variables[$name])) {
            $this->variables[$name] = new RuleBuilder\Variable($this, $name);
        }

        return $this->variables[$name];
    }

    /**
     * Set the default value of a Variable.
     *
     * @param string $name  The Variable name
     * @param mixed  $value The Variable default value
     *
     * @return Variable
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

    /**
     * Find an operator in the registered operator namespaces.
     *
     * @throws \LogicException If a matching operator is not found.
     *
     * @param string $name
     *
     * @return string
     */
    public function findOperator($name)
    {
        $operator = ucfirst($name);
        foreach (array_keys($this->operatorNamespaces) as $namespace) {
            $class = $namespace . '\\' . $operator;
            if (class_exists($class)) {
                return $class;
            }
        }

        throw new \LogicException(sprintf('Unknown operator: "%s"', $name));
    }
}
