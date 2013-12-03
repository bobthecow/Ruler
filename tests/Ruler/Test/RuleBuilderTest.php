<?php

namespace Ruler\Test;

use Ruler\RuleBuilder;
use Ruler\Context;
use Ruler\Test\Fixtures\TrueProposition;
use Ruler\Test\Fixtures\FalseProposition;
use Ruler\Value;

class RuleBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $rb = new RuleBuilder();
        $this->assertInstanceOf('Ruler\RuleBuilder', $rb);
        $this->assertInstanceOf('ArrayAccess', $rb);
    }

    public function testManipulateVariablesViaArrayAccess()
    {
        $name = 'alpha';
        $rb = new RuleBuilder();

        $this->assertFalse(isset($rb[$name]));

        $var = $rb[$name];
        $this->assertTrue(isset($rb[$name]));

        $this->assertInstanceOf('Ruler\Variable', $var);
        $this->assertEquals($name, $var->getName());

        $this->assertSame($var, $rb[$name]);
        $this->assertNull($var->getValue());

        $rb[$name] = 'eeesh.';
        $this->assertEquals('eeesh.', $var->getValue());

        unset($rb[$name]);
        $this->assertFalse(isset($rb[$name]));
        $this->assertNotSame($var, $rb[$name]);
    }

    public function testLogicalOperatorGeneration()
    {
        $rb      = new RuleBuilder();
        $context = new Context();

        $true  = new TrueProposition();
        $false = new FalseProposition();

        $this->assertInstanceOf('Ruler\Operator\LogicalAnd', $rb->logicalAnd($true, $false));
        $this->assertFalse($rb->logicalAnd($true, $false)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\LogicalOr', $rb->logicalOr($true, $false));
        $this->assertTrue($rb->logicalOr($true, $false)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\LogicalNot', $rb->logicalNot($true));
        $this->assertFalse($rb->logicalNot($true)->evaluate($context));

        $this->assertInstanceOf('Ruler\Operator\LogicalXor', $rb->logicalXor($true, $false));
        $this->assertTrue($rb->logicalXor($true, $false)->evaluate($context));
    }

    public function testRuleCreation()
    {
        $rb      = new RuleBuilder();
        $context = new Context();

        $true  = new TrueProposition();
        $false = new FalseProposition();

        $this->assertInstanceOf('Ruler\Rule', $rb->create($true));
        $this->assertTrue($rb->create($true)->evaluate($context));
        $this->assertFalse($rb->create($false)->evaluate($context));

        $executed = false;
        $rule = $rb->create($true, function() use (&$executed) {
            $executed = true;
        });

        $this->assertFalse($executed);
        $rule->execute($context);
        $this->assertTrue($executed);
    }

    public function testNotAddEqualTo()
    {
        $rb = new RuleBuilder();
        $context = new Context(array(
            'A2' => 8,
            'A3' => 4,
            'B2' => 13
        ));

        $rule = $rb->logicalNot(
            $rb['A2']->equalTo($rb['B2'])
        );
        $this->assertTrue($rule->evaluate($context));

        $rule = $rb->logicalNot(
            $rb['A2']->add($rb['A3'])->equalTo($rb['B2'])
        );
        $this->assertTrue($rule->evaluate($context));
    }

    public function testCallbackPropositionOnBuilder()
    {
        $rb = new RuleBuilder();
        $context = new Context();
        $test = $this;

        $rule = $rb->logicalNot(
            $rb->callbackProposition(
                function ($c) use ($context, $test) {
                    $test->assertSame($c, $context);
                    return false;
                }
            )
        );

        $this->assertTrue($rule->evaluate($context));
    }

    public function testCallbackPropositionOnVariable()
    {
        $rb = new RuleBuilder();
        $context = new Context(array(
            'thing' => 27
        ));
        $test = $this;

        $rule = $rb->logicalNot(
            $rb['thing']->callbackProposition(
                function ($c, Value $thing) use ($context, $test) {
                    $test->assertSame($c, $context);
                    return 27 != $thing->getValue();
                }
            )
        );

        $this->assertTrue($rule->evaluate($context));
    }

    public function testCallbackVariableOnBuilder()
    {
        $rb = new RuleBuilder();
        $context = new Context(array(
            'other' => 30
        ));
        $test = $this;

        $ran = false;
        $rule = $rb->logicalNot(
            $rb->callbackVariable(
                function ($c) use ($context, $test, &$ran) {
                    $ran = true;
                    $test->assertSame($c, $context);
                    return 30;
                }
            )->equalTo($rb['other'])
        );

        $this->assertFalse($ran);
        $this->assertFalse($rule->evaluate($context));
        $this->assertTrue($ran);
    }

    public function testCallbackVariableOnVariable()
    {
        $rb = new RuleBuilder();
        $context = new Context(array(
            'this' => 27,
            'that' => 3,
            'other' => 30
        ));
        $test = $this;

        $ran = false;
        $rule = $rb->logicalNot(
            $rb['this']->add($rb['that'])->callbackVariable(
                function ($c, Value $given) use ($context, $test, &$ran) {
                    $ran = true;
                    $test->assertSame($c, $context);
                    return $given->getValue();
                }
            )->equalTo($rb['other'])
        );

        $this->assertFalse($ran);
        $this->assertFalse($rule->evaluate($context));
        $this->assertTrue($ran);
    }

    public function testCallbackVariableMultiple()
    {
        $rb = new RuleBuilder();
        $context = new Context(array(
            'this' => 27,
            'that' => 3,
            'other' => 30
        ));
        $test = $this;

        $ran = false;
        $rule = $rb->logicalNot(
            $rb['this']->add($rb['that'])->callbackVariable(
                function ($c, Value $given, Value $twentySeven, Value $thirty) use ($context, $test, &$ran) {
                    $ran = true;
                    $test->assertSame($c, $context);
                    $test->assertEquals(27, $twentySeven->getValue());
                    $test->assertEquals(30, $thirty->getValue());
                    return $given->getValue();
                },
                $rb['that']->exponentiate(3),
                30
            )->equalTo($rb['other'])
        );

        $this->assertFalse($ran);
        $this->assertFalse($rule->evaluate($context));
        $this->assertTrue($ran);
    }

    public function testReadme()
    {
        $rb = new RuleBuilder();
        $context = new Context(array(
            'this' => 4,
            'that' => 2,
            'other' => 6000
        ));
        $rule = $rb['this']->add($rb['that'])->callbackVariable(
            function ($c, Value $given) {
                return $given->getValue() * 1000;
            }
        )->equalTo($rb['other']);

        //evaluates to true
        $this->assertTrue($rule->evaluate($context));

        $rule = $rb->callbackProposition(
            function ($c, Value $that) {
                return $that->getValue() == $c['that'];
            },
            $rb['that']
        );

        $this->assertTrue($rule->evaluate($context));
    }
}
