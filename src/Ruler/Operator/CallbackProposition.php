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
use Ruler\Proposition;

/**
 * @author Jordan Raub <jordan@raub.me>
 */
class CallbackProposition extends CallbackOperator implements Proposition
{
    public function evaluate(Context $context)
    {
        return $this->runCallback($context);
    }
}
