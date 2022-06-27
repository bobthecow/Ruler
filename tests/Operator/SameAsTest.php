<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class SameAsTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', 1);

        $op = new Operator\SameAs($varA, $varB);
        $this->assertInstanceOf(\Ruler\Proposition::class, $op);
    }

    public function testConstructorAndEvaluation()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', 2);
        $context = new Context();

        $op = new Operator\SameAs($varA, $varB);
        $this->assertFalse($op->evaluate($context));

        $context['a'] = 2;
        $this->assertTrue($op->evaluate($context));

        $context['a'] = 3;
        $context['b'] = function () {
            return 3;
        };
        $this->assertTrue($op->evaluate($context));

        $context['a'] = 3;
        $context['b'] = '3';
        $this->assertFalse($op->evaluate($context));

        $context['a'] = new \stdClass();
        $context['a']->attributes = 1;
        $context['b'] = new \stdClass();
        $context['b']->attributes = 1;
        $this->assertFalse($op->evaluate($context));

        $context['b'] = $context['a'];
        $this->assertTrue($op->evaluate($context));

        $context['a'] = 1;
        $context['b'] = true;
        $this->assertFalse($op->evaluate($context));

        $context['a'] = null;
        $context['b'] = false;
        $this->assertFalse($op->evaluate($context));
    }
}
