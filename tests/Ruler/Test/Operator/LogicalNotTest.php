<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Test\Fixtures\TrueProposition;
use Ruler\Test\Fixtures\FalseProposition;

class LogicalNotTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $true = new TrueProposition();

        $op = new Operator\LogicalNot($true);
        $this->assertInstanceOf('Ruler\Proposition', $op);
        $this->assertInstanceOf('Ruler\Operator\LogicalOperator', $op);
    }

    public function testConstructor()
    {
        $op = new Operator\LogicalNot(new FalseProposition());
        $this->assertTrue($op->evaluate(new Context()));
    }
}
