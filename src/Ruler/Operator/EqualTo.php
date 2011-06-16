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
 * An EqualTo comparison operator.
 *
 * @author  Justin Hileman <justin@shopopensky.com>
 * @extends ComparisonOperator
 */
class EqualTo extends ComparisonOperator
{
    /**
     * Evaluate whether the given variables are equal in the current Context.
     *
     * @param  Context $context
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        return $this->left->prepareValue($context)->equalTo($this->right->prepareValue($context));
    }
}