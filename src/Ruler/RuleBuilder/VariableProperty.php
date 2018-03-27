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

use Ruler\Variable as BaseVariable;

/**
 * A propositional VariableProperty.
 *
 * A VariableProperty is a special propositional Variable which maps to a
 * property, method or offset of another Variable. During evaluation, they are
 * replaced with terminal Values from properties of their parent Variable,
 * either from their default Value, or from the current Context.
 *
 * The RuleBuilder VariableProperty extends the base VariableProperty class with
 * a fluent interface for creating VariableProperties, Operators and Rules
 * without all kinds of awkward object instantiation.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
class VariableProperty extends Variable implements VariablePropertyInterface
{
    use VariablePropertyTrait;

    /**
     * VariableProperty class constructor.
     *
     * @param BaseVariable $parent Parent Variable instance
     * @param string       $name   Property name
     * @param mixed        $value  Default Property value (default: null)
     */
    public function __construct(BaseVariable $parent, $name, $value = null)
    {
        $this->parent = $parent;

        parent::__construct($parent->getRuleBuilder(), $name, $value);
    }
}
