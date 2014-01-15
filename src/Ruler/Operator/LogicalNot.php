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
 * A logical NOT operator.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 * @extends LogicalOperator
 */
class LogicalNot extends LogicalOperator
{
    protected $proposition;

    /**
     * Logical NOT constructor
     *
     * Logical NOT is unable to process multiple child Propositions, so passing an array with
     * more than one Proposition will result in a LogicException.
     *
     * @param array $props Child Proposition (default:null)
     *
     * @throws \LogicException
     */
    public function __construct(array $props = null)
    {
        if ($props !== null) {
            if (count($props) != 1) {
                throw new \LogicException('Logical Not requires exactly one proposition');
            }

            $this->proposition = array_pop($props);
        }
    }

    /**
     * Set the child Proposition.
     *
     * Logical NOT is unable to process multiple child Propositions, so calling addProposition
     * if a Proposition has already been set will result in a LogicException.
     *
     * @param Proposition $prop Child Proposition
     *
     * @throws LogicException
     */
    public function addProposition(Proposition $prop)
    {
        if (isset($this->proposition)) {
            throw new \LogicException('Logical Not requires exactly one proposition');
        }

        $this->proposition = $prop;
    }

    /**
     * Evaluate whether the child Proposition evaluates to false given the current Context.
     *
     * @param Context $context Context with which to evaluate this LogicalOperator
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        if (!isset($this->proposition)) {
            throw new \LogicException('Logical Not requires exactly one proposition');
        }

        return !$this->proposition->evaluate($context);
    }
}