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

/**
 * @author Jordan Raub <jordan@raub.me>
 */
interface VariableOperand
{
    /**
     * @param Context $context
     *
     * @return Value
     */
    public function prepareValue(Context $context);
}
