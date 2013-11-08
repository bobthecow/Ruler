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
 * A DoesNotContain comparison operator.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 * @extends ComparisonOperator
 */
class DoesNotContain extends ComparisonOperator
{
    /**
     * @param Value $left
     * @param Value $right
     *
     * @return bool
     */
    protected function evaluatePrepared(Value $left, Value $right)
    {
        return $left->contains($right) === false;
    }
}
