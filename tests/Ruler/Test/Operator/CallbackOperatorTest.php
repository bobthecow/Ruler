<?php

namespace Ruler\Test\Operator;

use Ruler\Operator\CallbackProposition;
use Ruler\Operator\CallbackVariableOperand;
use Ruler\Operator\EqualTo;
use Ruler\Rule;
use Ruler\Proposition;
use Ruler\Context;
use Ruler\Test\Fixtures\FalseProposition;
use Ruler\Test\Fixtures\TrueProposition;
use Ruler\Value;
use Ruler\Variable;

class CallbackOperatorTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyCallbackProposition()
    {
        $ran = false;
        $test = $this;
        $context = new Context();
        $rule = new Rule(
            new CallbackProposition(function ($ctxt) use (&$ran, $context, $test) {
                $test->assertSame($ctxt, $context);
                $ran = true;
                return true;
            })
        );

        $this->assertFalse($ran);
        $this->assertTrue($rule->evaluate($context));
        $this->assertTrue($ran);
    }

    public function testEmptyCallbackVariableOperand()
    {
        $ran = false;
        $test = $this;
        $context = new Context();
        $rule = new Rule(
            new EqualTo(
                new CallbackVariableOperand(function ($ctxt) use (&$ran, $context, $test) {
                    $test->assertSame($ctxt, $context);
                    $ran = true;
                    return 27;
                }),
                new Variable(27, 27)
            )
        );

        $this->assertFalse($ran);
        $this->assertTrue($rule->evaluate($context));
        $this->assertTrue($ran);
    }

    public function testCallbackProposition()
    {
        $ran = false;
        $test = $this;
        $context = new Context();
        $rule = new Rule(
            new CallbackProposition(
                function ($ctxt, $one, $two, $three, $four) use (&$ran, $test, $context) {
                    $test->assertSame($ctxt, $context);
                    $test->assertInternalType('bool', $one);
                    $test->assertInternalType('bool', $two);
                    $test->assertInstanceOf('\\Ruler\\Value', $three);
                    $test->assertInstanceOf('\\Ruler\\Value', $four);
                    $test->assertFalse($one);
                    $test->assertTrue($two);
                    $test->assertFalse($three->getValue());
                    $test->assertTrue($four->getValue());
                    $ran = true;
                    return true;
                },
                new FalseProposition(),
                new TrueProposition(),
                new Variable('blah', false),
                new Variable('bloop', true)
            )
        );

        $this->assertFalse($ran);
        $this->assertTrue($rule->evaluate($context));
        $this->assertTrue($ran);
    }

    public function testCallbackVariableOperand()
    {
        $ran = false;
        $context = new Context(array(
            'one' => 1,
            'two' => 2,
            'three' => 3
        ));
        $test = $this;
        $rule = new Rule(
            new EqualTo(
                new CallbackVariableOperand(
                    function ($ctxt, $one, $two, $three) use (&$ran, $test, $context) {
                        $test->assertSame($ctxt, $context);
                        $test->assertEquals(1, $one->getValue());
                        $test->assertFalse($two);
                        $test->assertEquals(3, $three->getValue());
                        $ran = true;
                        return $three;
                    },
                    new Variable('one'),
                    new FalseProposition(),
                    new Variable('three')
                ),
                new Variable('three')
            )
        );

        $this->assertFalse($ran);
        $this->assertTrue($rule->evaluate($context));
        $this->assertTrue($ran);
    }
}
