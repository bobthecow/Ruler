<?php

/*
 * This file is part of the Ruler package, an OpenSky project.
 *
 * (c) 2011 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ruler\Test\Fixtures;

use Ruler\Context;
use Ruler\Value;
use Ruler\Operator\ComparisonOperator;

/**
 * An EqualTo comparison operator.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 * @extends ComparisonOperator
 */
class ALotGreaterThan extends ComparisonOperator
{
    /**
     * Evaluate whether the given variables are equal in the current Context.
     *
     * @param Context $context Context with which to evaluate this ComparisonOperator
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        $value = $this->right->prepareValue($context)->getValue() * 10;
        return $this->left->prepareValue($context)->greaterThan(new Value($value));
    }
}

