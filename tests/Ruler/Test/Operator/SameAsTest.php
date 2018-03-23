<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;
use PHPUnit\Framework\TestCase;

class SameAsTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', 1);

        $op = new Operator\SameAs($varA, $varB);
        $this->assertInstanceOf('Ruler\Proposition', $op);
    }

    public function testConstructorAndEvaluation()
    {
        $varA    = new Variable('a', 1);
        $varB    = new Variable('b', 2);
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

        $context['a'] = new \StdClass();
        $context['a']->attributes = 1;
        $context['b'] = new \StdClass();
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
