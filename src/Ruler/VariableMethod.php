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
 * A Variable Method.
 *
 * Variable Methods are placeholders in Propositions and Comparison Operators. During
 * evaluation, they are replaced with terminal Values by executing the method.
 *
 * @author Matias Griese <matias@rockettheme.com>
 */
class VariableMethod extends Variable
{

    /**
     * Prepare a Value for this VariableMethod given the current Context.
     *
     * @param Context $context The current Context
     *
     * @return Value
     */
    public function prepareValue(Context $context)
    {
        $params = [];
        foreach ((array)$this->value as $param) {
            $params[] = ($param instanceof Variable) ? $param->prepareValue($context)->getValue() : $param;
        }
        $func = $context[$this->name];

        return new Value(call_user_func_array($func, $params));
    }
}