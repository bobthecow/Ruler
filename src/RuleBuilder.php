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
    private array $variables = [];
    private array $operatorNamespaces = [];

    /**
     * Create a Rule with the given propositional condition.
     *
     * @param Proposition $condition Propositional condition for this Rule
     * @param callable    $action    Action (callable) to take upon successful Rule execution (default: null)
     */
    public function create(Proposition $condition, $action = null): Rule
    {
        return new Rule($condition, $action);
    }

    /**
     * Register an operator namespace.
     *
     * Note that, depending on your filesystem, operator namespaces are most likely case sensitive.
     *
     * @throws \InvalidArgumentException
     */
    public function registerOperatorNamespace(string $namespace): self
    {
        if (!\is_string($namespace)) {
            throw new \InvalidArgumentException('Namespace argument must be a string');
        }

        $this->operatorNamespaces[$namespace] = true;

        return $this;
    }

    /**
     * Create a logical AND operator proposition.
     *
     * @param Proposition ...$props One or more Propositions
     */
    public function logicalAnd(Proposition ...$props): Operator\LogicalAnd
    {
        return new Operator\LogicalAnd($props);
    }

    /**
     * Create a logical OR operator proposition.
     *
     * @param Proposition ...$props One or more Propositions
     */
    public function logicalOr(Proposition ...$props): Operator\LogicalOr
    {
        return new Operator\LogicalOr($props);
    }

    /**
     * Create a logical NOT operator proposition.
     *
     * @param Proposition $prop Exactly one Proposition
     */
    public function logicalNot(Proposition $prop): Operator\LogicalNot
    {
        return new Operator\LogicalNot([$prop]);
    }

    /**
     * Create a logical XOR operator proposition.
     *
     * @param Proposition ...$props One or more Propositions
     */
    public function logicalXor(Proposition ...$props): Operator\LogicalXor
    {
        return new Operator\LogicalXor($props);
    }

    /**
     * Check whether a Variable is already set.
     *
     * @param string $name The Variable name
     */
    public function offsetExists($name): bool
    {
        return isset($this->variables[$name]);
    }

    /**
     * Retrieve a Variable by name.
     *
     * @param string $name The Variable name
     */
    public function offsetGet($name): RuleBuilder\Variable
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
     */
    public function offsetSet($name, $value): void
    {
        $this->offsetGet($name)->setValue($value);
    }

    /**
     * Remove a defined Variable from the RuleBuilder.
     *
     * @param string $name The Variable name
     */
    public function offsetUnset($name): void
    {
        unset($this->variables[$name]);
    }

    /**
     * Find an operator in the registered operator namespaces.
     *
     * @throws \LogicException if a matching operator is not found
     */
    public function findOperator(string $name): string
    {
        $operator = \ucfirst($name);
        foreach (\array_keys($this->operatorNamespaces) as $namespace) {
            $class = $namespace.'\\'.$operator;
            if (\class_exists($class)) {
                return $class;
            }
        }

        throw new \LogicException(\sprintf('Unknown operator: "%s"', $name));
    }
}
