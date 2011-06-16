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

/**
 * A logical AND operator.
 *
 * @author  Justin Hileman <justin@shopopensky.com>
 * @extends LogicalOperator
 */
class LogicalAnd extends LogicalOperator
{
    /**
     * Evaluate whether all child Propositions evaluate to true given the current Context.
     *
     * @param  Context $context
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        if (empty($this->propositions)) {
            throw new \LogicException('Logical And requires at least one proposition');
        }

        foreach ($this->propositions as $prop) {
            if ($prop->evaluate($context) === false) {
                return false;
            }
        }

        return true;
    }
}