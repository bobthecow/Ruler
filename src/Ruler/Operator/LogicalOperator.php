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

use Ruler\Proposition;

/**
 * Abstract Logical Operator class.
 *
 * Logical Operators represent propositional operations: AND, OR, NOT and XOR.
 *
 * @abstract
 * @author     Justin Hileman <justin@shopopensky.com>
 * @implements Proposition
 */
abstract class LogicalOperator implements Proposition
{
    protected $propositions = array();

    /**
     * Logical Operator constructor.
     *
     * @param array $props Initial Propositions to add to the Operator (default: null)
     */
    public function __construct(array $props = null)
    {
        if ($props !== null) {
            foreach ($props as $prop) {
                $this->addProposition($prop);
            }
        }
    }

    /**
     * Add a Proposition to the Operator.
     *
     * @param Proposition $prop
     */
    public function addProposition(Proposition $prop)
    {
        $this->propositions[] = $prop;
    }
}
