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
 * A HasNoIntersection operator.
 *
 * @author Enrico Baioni <enrico.baioni@gmail.com>
 * @extends ComparisonOperator
 */
class HasNoIntersection extends ComparisonOperator
{
    /**
     * Evaluate whether the left variable has no elements in common with the right in the current Context.
     *
     * @param Context $context Context with which to evaluate this ComparisonOperator
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        return $this->left->prepareValue($context)->hasIntersection($this->right->prepareValue($context)) === false;
    }
}
