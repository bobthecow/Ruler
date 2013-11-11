<?php

namespace Ruler\Test\Operator;

use Ruler\Operator;
use Ruler\Context;
use Ruler\Test\Fixtures\TrueProposition;
use Ruler\Test\Fixtures\FalseProposition;

class LogicalXorTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $true = new TrueProposition();

        $op = new Operator\LogicalXor(array($true));
        $this->assertInstanceOf('Ruler\Proposition', $op);
    }

    public function testConstructor()
    {
        $true    = new TrueProposition();
        $false   = new FalseProposition();
        $context = new Context();

        $op = new Operator\LogicalXor(array($true, $false));
        $this->assertTrue($op->evaluate($context));
    }

    public function testAddPropositionAndEvaluate()
    {
        $true    = new TrueProposition();
        $false   = new FalseProposition();
        $context = new Context();

        $op = new Operator\LogicalXor();

        $op->addProposition($false);
        $this->assertFalse($op->evaluate($context));

        $op->addOperand($false);
        $this->assertFalse($op->evaluate($context));

        $op->addProposition($true);
        $this->assertTrue($op->evaluate($context));

        $op->addOperand($true);
        $this->assertFalse($op->evaluate($context));
    }

    /**
     * @expectedException \LogicException
     */
    public function testExecutingALogicalXorWithoutPropositionsThrowsAnException()
    {
        $op = new Operator\LogicalXor();
        $op->evaluate(new Context());
    }
}
