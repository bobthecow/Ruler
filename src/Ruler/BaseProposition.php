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
 * Aggregation of Proposition and VariableOperand interface.
 *
 * An attempt to make (refactor) all propositional operators and variables stick
 * to the same contract. After all everything could be a proposition (even Variables)
 * in Ruler.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
interface BaseProposition
{

    /**
     * Evaluate the Proposition with the given Context.
     *
     * @param Context $context Context with which to evaluate this Proposition
     *
     * @return boolean
     */
    public function evaluate(Context $context);

    /**
     * @param Context $context
     *
     * @return Value
     */
    public function prepareValue(Context $context);
}
