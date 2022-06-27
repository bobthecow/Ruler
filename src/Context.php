<?php

/*
 * This file is part of the Ruler package, an OpenSky project.
 *
 * Copyright (c) 2009 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Ruler;

/**
 * Ruler Context.
 *
 * The Context contains facts with which to evaluate a Rule or other Proposition.
 *
 * Derived from Pimple, by Fabien Potencier:
 *
 * https://github.com/fabpot/Pimple
 *
 * @author Fabien Potencier
 * @author Justin Hileman <justin@justinhileman.info>
 */
class Context implements \ArrayAccess
{
    private array $keys = [];
    private array $values = [];
    private array $frozen = [];
    private array $raw = [];

    private $shared;
    private $protected;

    /**
     * Context constructor.
     *
     * Optionally, bootstrap the context by passing an array of fact names and
     * values.
     *
     * @param array $values (default: array())
     */
    public function __construct(array $values = [])
    {
        $this->shared = new \SplObjectStorage();
        $this->protected = new \SplObjectStorage();

        foreach ($values as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * Check if a fact is defined.
     *
     * @param string $name The unique name for the fact
     */
    public function offsetExists($name): bool
    {
        return isset($this->keys[$name]);
    }

    /**
     * Get the value of a fact.
     *
     * @param string $name The unique name for the fact
     *
     * @throws \InvalidArgumentException if the name is not defined
     *
     * @return mixed The resolved value of the fact
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($name)
    {
        if (!$this->offsetExists($name)) {
            throw new \InvalidArgumentException(\sprintf('Fact "%s" is not defined.', $name));
        }

        $value = $this->values[$name];

        // If the value is already frozen, or if it's not callable, or if it's protected, return the raw value
        if (isset($this->frozen[$name]) || !\is_object($value) || $this->protected->contains($value) || !$this->isCallable($value)) {
            return $value;
        }

        // If this is a shared value, resolve, freeze, and return the result
        if ($this->shared->contains($value)) {
            $this->frozen[$name] = true;
            $this->raw[$name] = $value;

            return $this->values[$name] = $value($this);
        }

        // Otherwise, resolve and return the result
        return $value($this);
    }

    /**
     * Set a fact name and value.
     *
     * A fact will be lazily evaluated if it is a Closure or invokable object.
     * To define a fact as a literal callable, use Context::protect.
     *
     * @param string $name  The unique name for the fact
     * @param mixed  $value The value or a closure to lazily define the value
     *
     * @throws \RuntimeException if a frozen fact overridden
     */
    public function offsetSet($name, $value): void
    {
        if (isset($this->frozen[$name])) {
            throw new \RuntimeException(\sprintf('Cannot override frozen fact "%s".', $name));
        }

        $this->keys[$name] = true;
        $this->values[$name] = $value;
    }

    /**
     * Unset a fact.
     *
     * @param string $name The unique name for the fact
     */
    public function offsetUnset($name): void
    {
        if ($this->offsetExists($name)) {
            $value = $this->values[$name];

            if (\is_object($value)) {
                $this->shared->detach($value);
                $this->protected->detach($value);
            }

            unset($this->keys[$name], $this->values[$name], $this->frozen[$name], $this->raw[$name]);
        }
    }

    /**
     * Define a fact as "shared". This lazily evaluates and stores the result
     * of the callable for the scope of this Context instance.
     *
     * @param callable $callable A fact callable to share
     *
     * @throws \InvalidArgumentException if the callable is not a Closure or invokable object
     *
     * @return callable The passed callable
     */
    public function share($callable)
    {
        if (!$this->isCallable($callable)) {
            throw new \InvalidArgumentException('Value is not a Closure or invokable object.');
        }

        $this->shared->attach($callable);

        return $callable;
    }

    /**
     * Protect a callable from being interpreted as a lazy fact definition.
     *
     * This is useful when you want to store a callable as the literal value of
     * a fact.
     *
     * @param callable $callable A callable to protect from being evaluated
     *
     * @throws \InvalidArgumentException if the callable is not a Closure or invokable object
     *
     * @return callable The passed callable
     */
    public function protect($callable)
    {
        if (!$this->isCallable($callable)) {
            throw new \InvalidArgumentException('Callable is not a Closure or invokable object.');
        }

        $this->protected->attach($callable);

        return $callable;
    }

    /**
     * Get a fact or the closure defining a fact.
     *
     * @param string $name The unique name for the fact
     *
     * @throws \InvalidArgumentException if the name is not defined
     *
     * @return mixed The value of the fact or the closure defining the fact
     */
    public function raw($name)
    {
        if (!$this->offsetExists($name)) {
            throw new \InvalidArgumentException(\sprintf('Fact "%s" is not defined.', $name));
        }

        if (isset($this->frozen[$name])) {
            return $this->raw[$name];
        }

        return $this->values[$name];
    }

    /**
     * Get all defined fact names.
     */
    public function keys(): array
    {
        return \array_keys($this->keys);
    }

    /**
     * Check whether a value is a Closure or invokable object.
     *
     * @param mixed $callable
     */
    protected function isCallable($callable): bool
    {
        return \is_object($callable) && \is_callable($callable);
    }
}
