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

use Ruler\Value;

/**
 * An NotSameAs comparison operator.
 *
 * @author Christophe Sicard <sicard.christophe@gmail.com>
 * @extends ComparisonOperator
 */
class NotSameAs extends ComparisonOperator
{
    /**
     * Evaluate whether the given variables are not identical in the current Context.
     *
     * @param Value $left
     * @param Value $right
     *
     * @return boolean
     */
    public function evaluatePrepared(Value $left, Value $right)
    {
        return $left->sameAs($right) === false;
    }
}
