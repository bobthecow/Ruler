<?php

namespace Ruler\Test;

use Ruler\Operator\GreaterThanOrEqualTo;
use Ruler\Operator\LessThanOrEqualTo;
use Ruler\Operator\LogicalAnd;
use Ruler\Rule;
use Ruler\Proposition;
use Ruler\Context;
use Ruler\Test\Fixtures\TrueProposition;
use Ruler\Test\Fixtures\CallbackProposition;
use Ruler\Variable;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $rule = new Rule(new TrueProposition());
        $this->assertInstanceOf('Ruler\Proposition', $rule);
    }

    public function testConstructorEvaluationAndExecution()
    {
        $test           = $this;
        $context        = new Context();
        $executed       = false;
        $actionExecuted = false;

        $ruleOne = new Rule(
            new CallbackProposition(function ($c) use ($test, $context, &$executed, &$actionExecuted) {
                $test->assertSame($c, $context);
                $executed = true;
                return false;
            }),
            function() use ($test, &$actionExecuted) {
                $actionExecuted = true;
            }
        );

        $this->assertFalse($ruleOne->evaluate($context));
        $this->assertTrue($executed);

        $ruleOne->execute($context);
        $this->assertFalse($actionExecuted);

        $executed       = false;
        $actionExecuted = false;

        $ruleTwo = new Rule(
            new CallbackProposition(function ($c) use ($test, $context, &$executed, &$actionExecuted) {
                $test->assertSame($c, $context);
                $executed = true;
                return true;
            }),
            function() use ($test, &$actionExecuted) {
                $actionExecuted = true;
            }
        );

        $this->assertTrue($ruleTwo->evaluate($context));
        $this->assertTrue($executed);

        $ruleTwo->execute($context);
        $this->assertTrue($actionExecuted);
    }

    /**
     * @expectedException \LogicException
     */
    public function testNonCallableActionsWillThrowAnException()
    {
        $context = new Context();
        $rule = new Rule(new TrueProposition(), 'this is not callable');
        $rule->execute($context);
    }

    public function testConditionCanBeReturned()
    {
        $actualNumPeople = new Variable('actualNumPeople');
        $rule = new Rule(
            new LogicalAnd(array(
                new LessThanOrEqualTo(new Variable('minNumPeople'), $actualNumPeople),
                new GreaterThanOrEqualTo(new Variable('maxNumPeople'), $actualNumPeople)
            )),
            function() {
                echo 'YAY!';
            }
        );

        $condition = $rule->getCondition();
        $this->assertInstanceOf('Ruler\Operator\LogicalAnd', $condition);
    }
}
