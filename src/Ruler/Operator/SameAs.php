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
 * An SameAs comparison operator.
 *
 * @author Christophe Sicard <sicard.christophe@gmail.com>
 * @extends ComparisonOperator
 */
class SameAs extends ComparisonOperator
{
    /**
     * Evaluate whether the given variables are identical in the current Context.
     *
     * @param Context $context Context with which to evaluate this ComparisonOperator
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        return $this->left->prepareValue($context)->sameAs($this->right->prepareValue($context));
    }
}
