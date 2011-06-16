<?php

/*
 * This file is part of the Ruler package, an OpenSky project.
 *
 * (c) 2011 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ruler;

use Ruler\Context;

/**
 * The Proposition interface represents a propositional statement.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 */
interface Proposition
{

    /**
     * Evaluate the Proposition with the given Context.
     *
     * @param Context $context
     * @return boolean
     */
    public function evaluate(Context $context);
}