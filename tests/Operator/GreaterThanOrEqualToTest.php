<?php

namespace Ruler\Test\Operator;

use PHPUnit\Framework\TestCase;
use Ruler\Context;
use Ruler\Operator;
use Ruler\Variable;

class GreaterThanOrEqualToTest extends TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', 2);

        $op = new Operator\GreaterThanOrEqualTo($varA, $varB);
        $this->assertInstanceOf(\Ruler\Proposition::class, $op);
    }

    public function testConstructorAndEvaluation()
    {
        $varA = new Variable('a', 1);
        $varB = new Variable('b', 2);
        $context = new Context();

        $op = new Operator\GreaterThanOrEqualTo($varA, $varB);
        $this->assertFalse($op->evaluate($context));

        $context['a'] = 2;
        $this->assertTrue($op->evaluate($context));

        $context['a'] = 3;
        $context['b'] = function () {
            return 3;
        };
        $this->assertTrue($op->evaluate($context));

        $context['4'] = 3;
        $this->assertTrue($op->evaluate($context));
    }
}
