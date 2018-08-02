<?php
namespace Ruler\RuleBuilder;

use Ruler\Context;
use Ruler\Value;
use Ruler\Variable as BaseVariable;

/**
 * Use this interface when creating custom Variable and VariableProperty
 * classes for extending the RuleBuilder DSL.
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
interface VariablePropertyInterface
{
    /**
     * Return the Variable name.
     *
     * @return string Variable name
     */
    public function getName();

    /**
     * Get the default Variable value.
     *
     * @return mixed Variable value
     */
    public function getValue();

    /**
     * Set the parent Variable reference.
     *
     * @param BaseVariable $parent Parent Variable instance
     */
    public function setParent(BaseVariable $parent);

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
    public function prepareValue(Context $context);
}
