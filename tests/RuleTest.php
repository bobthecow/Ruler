<?php

namespace Ruler\Test;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Rule;
use Ruler\Test\Fixtures\CallbackProposition;
use Ruler\Test\Fixtures\TrueProposition;

class RuleTest extends TestCase
{
    public function testInterface()
    {
        $rule = new Rule(new TrueProposition());
        $this->assertInstanceOf(\Ruler\Proposition::class, $rule);
    }

    public function testConstructorEvaluationAndExecution()
    {
        $test = $this;
        $context = new Context();
        $executed = false;
        $actionExecuted = false;

        $ruleOne = new Rule(
            new CallbackProposition(function ($c) use ($test, $context, &$executed, &$actionExecuted) {
                $test->assertSame($c, $context);
                $executed = true;

                return false;
            }),
            function () use (&$actionExecuted) {
                $actionExecuted = true;
            }
        );

        $this->assertFalse($ruleOne->evaluate($context));
        $this->assertTrue($executed);

        $ruleOne->execute($context);
        $this->assertFalse($actionExecuted);

        $executed = false;
        $actionExecuted = false;

        $ruleTwo = new Rule(
            new CallbackProposition(function ($c) use ($test, $context, &$executed, &$actionExecuted) {
                $test->assertSame($c, $context);
                $executed = true;

                return true;
            }),
            function () use (&$actionExecuted) {
                $actionExecuted = true;
            }
        );

        $this->assertTrue($ruleTwo->evaluate($context));
        $this->assertTrue($executed);

        $ruleTwo->execute($context);
        $this->assertTrue($actionExecuted);
    }

    public function testNonCallableActionsWillThrowAnException()
    {
        $this->expectException(\LogicException::class);
        $context = new Context();
        $rule = new Rule(new TrueProposition(), 'this is not callable');
        $rule->execute($context);
    }
}
