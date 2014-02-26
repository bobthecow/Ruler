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
 * A Ruler RuleSet.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
class RuleSet
{
    protected $rules = array();

    /**
     * RuleSet constructor.
     *
     * @param array $rules Rules to add to RuleSet
     */
    public function __construct(array $rules = array())
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * Add a Rule to the RuleSet.
     *
     * Adding duplicate Rules to the RuleSet will have no effect.
     *
     * @param Rule $rule Rule to add to the set
     */
    public function addRule(Rule $rule)
    {
        $this->rules[spl_object_hash($rule)] = $rule;
    }

    /**
     * Execute all Rules in the RuleSet.
     *
     * @param Context $context Context with which to execute each Rule
     */
    public function executeRules(Context $context)
    {
        foreach ($this->rules as $rule) {
            $rule->execute($context);
        }
    }
}
