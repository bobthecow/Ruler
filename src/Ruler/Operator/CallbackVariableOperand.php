<?php

/*
 * This file is part of the Ruler package, an OpenSky project.
 *
 * (c) 2011 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ruler\Operator;

use Ruler\Context;
use Ruler\Value;
use Ruler\VariableOperand;

/**
 * @author Jordan Raub <jordan@raub.me>
 */
class CallbackVariableOperand extends CallbackOperator implements VariableOperand
{
    public function prepareValue(Context $context)
    {
        $value = $this->runCallback($context);
        return $value instanceof Value ? $value : new Value($value);
    }
}
