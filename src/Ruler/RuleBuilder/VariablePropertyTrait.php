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

use Ruler\Context;
use Ruler\Value;
use Ruler\Variable;

/**
 * All the guts of the VariableProperty, but none of the PHP 5.3ness.
 *
 * PHP 5.4+ users: Use this trait when creating custom Variable and
 * VariableProperty classes for extending the RuleBuilder DSL.
 *
 * Everyone else: Ignore this, it's too cool for you.
 *
 * Apparently too cool for me, too, otherwise the VariableProperty classes in
 * this library would be using this trait.
 *
 * A VariableProperty is a special propositional Variable which maps to a
 * property, method or offset of another Variable. During evaluation, they are
 * replaced with terminal Values from properties of their parent Variable,
 * either from their default Value, or from the current Context.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
trait VariablePropertyTrait
{
    private $parent;

    /**
     * Set the parent Variable reference.
     *
     * @param Variable $parent Parent Variable instance
     */
    public function setParent(Variable $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Prepare a Value for this VariableProperty given the current Context.
     *
     * To retrieve a Value, the parent Variable is first resolved given the
     * current context. Then, depending on its type, a method, property or
     * offset of the parent Value is returned.
     *
     * If the parent Value is an object, and this VariableProperty name is
     * "bar", it will do a prioritized lookup for:
     *
     *  1. A method named `bar`
     *  2. A public property named `bar`
     *  3. ArrayAccess + offsetExists named `bar`
     *
     * If it is an array:
     *
     *  1. Array index `bar`
     *
     * Otherwise, return the default value for this VariableProperty.
     *
     * @param Context $context The current Context
     *
     * @return Value
     */
    public function prepareValue(Context $context)
    {
        $name  = $this->getName();
        $value = $this->parent->prepareValue($context)->getValue();

        if (is_object($value) && !$value instanceof \Closure) {
            if (method_exists($value, $name)) {
                return $this->asValue(call_user_func(array($value, $name)));
            } elseif (isset($value->$name)) {
                return $this->asValue($value->$name);
            } elseif ($value instanceof \ArrayAccess && $value->offsetExists($name)) {
                return $this->asValue($value->offsetGet($name));
            }
        } elseif (is_array($value) && array_key_exists($name, $value)) {
            return $this->asValue($value[$name]);
        }

        return $this->asValue($this->getValue());
    }

    /**
     * Private helper to retrieve a Value instance for the given $value.
     *
     * @param mixed $value Value instance or value
     *
     * @return Value
     */
    private function asValue($value)
    {
        return ($value instanceof Value) ? $value : new Value($value);
    }
}
