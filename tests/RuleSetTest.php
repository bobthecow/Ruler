<?php

namespace Ruler\Test;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Rule;
use Ruler\RuleSet;
use Ruler\Test\Fixtures\TrueProposition;

class RuleSetTest extends TestCase
{
    public function testRulesetCreationUpdateAndExecution()
    {
        $context = new Context();
        $true = new TrueProposition();

        $executedActionA = false;
        $ruleA = new Rule($true, function () use (&$executedActionA) {
            $executedActionA = true;
        });

        $executedActionB = false;
        $ruleB = new Rule($true, function () use (&$executedActionB) {
            $executedActionB = true;
        });

        $executedActionC = false;
        $ruleC = new Rule($true, function () use (&$executedActionC) {
            $executedActionC = true;
        });

        $ruleset = new RuleSet([$ruleA]);

        $ruleset->executeRules($context);

        $this->assertTrue($executedActionA);
        $this->assertFalse($executedActionB);
        $this->assertFalse($executedActionC);

        $ruleset->addRule($ruleC);
        $ruleset->executeRules($context);

        $this->assertTrue($executedActionA);
        $this->assertFalse($executedActionB);
        $this->assertTrue($executedActionC);
    }
}
