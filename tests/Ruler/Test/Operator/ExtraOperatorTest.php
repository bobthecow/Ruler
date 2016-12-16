<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Variable;

class ExtraOperatorTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorAndEvaluation()
    {
        $varA    = new Variable('a', 1);
        $varB    = new Variable('b', 2);
        $context = new Context();

        $op = new Operator\GreaterThan($varA, $varB);
        $this->assertFalse($op->evaluate($context));

        $context['a'] = 2;
        $this->assertFalse($op->evaluate($context));

        $context['a'] = 3;
        $context['b'] = function () {
            return 0;
        };
        $this->assertTrue($op->evaluate($context));
    }
}
