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
use Ruler\Variable;

/**
 * An Exists comparison operator.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 * @extends ComparisonOperator
 */
class Exists extends ComparisonOperator
{
    /**
     * Comparison Operator constructor.
     *
     * @param Variable $left  Left side of comparison
     */
    public function __construct(Variable $left)
    {
        $this->left = $left;
    }

    /**
     * Evaluate whether the left variable exists in the current Context.
     *
     * In our context, exists means that the key exists (even if its value is null)
     *
     * @param Context $context Context with which to evaluate this ComparisonOperator
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        return isset($context[$this->left->getName()]);
    }
}
