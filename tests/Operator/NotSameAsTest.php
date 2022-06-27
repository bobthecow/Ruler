<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class NotSameAsTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', 2);

        $op = new Operator\NotSameAs($varA, $varB);
        $this->assertInstanceOf(\Ruler\Proposition::class, $op);
    }

    public function testConstructorAndEvaluation()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', 2);
        $context = new Context();

        $op = new Operator\NotSameAs($varA, $varB);
        $this->assertTrue($op->evaluate($context));

        $context['a'] = 2;
        $this->assertFalse($op->evaluate($context));

        $context['a'] = 3;
        $context['b'] = function () {
            return 3;
        };
        $this->assertFalse($op->evaluate($context));

        $context['a'] = 1;
        $this->assertTrue($op->evaluate($context));
    }
}
